<?php

namespace App\Http\Controllers;

use Session;
use JavaScript;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Facades\Log;
use App\Services\NetPay as NetPayService;
use App\Http\Controllers\Traits\HasPaypal;
use Srmklive\PayPal\Services\ExpressCheckout;
use Illuminate\Contracts\Session\Session as SessionSession;

class CheckoutController extends Controller
{
    use fetchCart, HasPaypal;

    public function get_paf_data(Request $request)
    {
        \Log::info("CheckoutController get_paf_data()");
        \Log::debug(json_encode($request->all()));
        $data['post_code'] = $request->post_code;
        $data['client_ref'] = 'DA4B9237BACCCDF19C0760CAB7AEC4A8359010B0';

        // call to curl_helper->curl()
        $pafData = Curl::to('https://paf.tscube.co.in/paf.php')
            ->withData($data)
            ->post();

        $pafData = json_decode($pafData, true);

        $table = '';
        $i = 1;
        if ($pafData) {

            foreach ($pafData as $value) {

                $script = '$(this).parent().parent().trigger("dblclick")';
                $table .= "<tr>
                                <td>" . $i . "</td>
                                <td>" . $value['address1'] . "</td>
                                <td>" . $value['address2'] . "</td>
                                <td>" . $value['postcode'] . "</td>
                                <td>" . $value['post_town'] . "</td>
                                <td>" . $value['post_town'] . "</td>
                                <td><a onclick='" . $script . "' class='btn btn-xs btn-success'>Select</a></td>
                            </tr>";
                $i++;

            }
        }

        return $table;

    }

    public function city(Request $request)
    {
        \Log::info("CheckoutController city()");
        \Log::debug(json_encode($request->all()));
        $cityData = '';
        $country_id = $request->country_id;
        $citys = Curl::to($this->api_url . 'donor/city/' . $country_id)
            ->withBearer($this->token)->withOption('USERAGENT', 'DonationPortal/1.0')
            ->asJson()
            ->get();

        if ($citys) {
            foreach ($citys as $city) {
                $selected = $city->city_id == $request->city_id ? "selected" : '';
                $cityData .= '<option value="' . $city->city_id . '" ' . $selected . '>' . $city->city_name . '</option>';
            }
        }

        return $cityData;
    }

    public function city_name(Request $request)
    {
        \Log::info("CheckoutController city_name()");
        \Log::debug(json_encode($request->all()));
        $data['city_name'] = $request->city_name;
        $citys = Curl::to($this->api_url . 'donor/city_name')
            ->withBearer($this->token)->withOption('USERAGENT', 'DonationPortal/1.0')
            ->withData($data)
            ->asJson()
            ->post();

        return ($citys[0]->city_id);
    }

    public function addNewAddress(Request $request)
    {
        \Log::info("CheckoutController addNewAddress()");
        \Log::debug(json_encode($request->all()));
        /* Donor Details */

        $data['donor_id'] = $request->session()->get('user')['donor_id'];
        $data['country_id'] = $request->country_id;
        $data['post_code'] = $request->post_code;
        $data['address1'] = $request->address1;
        $data['address2'] = $request->address2;
        $data['city_id'] = $request->city_id;

        Log::info($data);

        $response = Curl::to($this->api_url . 'donor/add-new-address')
            ->withBearer($this->token)->withOption('USERAGENT', 'DonationPortal/1.0')
            ->withData($data)
            ->asJson()
            ->post();
        if ($response->success) {
            return response()->json([
                'success' => true,
            ]);
        } else {
            return false;
        }
    }

    public function index(Request $request)
    {
        \Log::info("CheckoutController index()");
        \Log::debug(json_encode($request->all()));
        $this->fetchCartData($request);

        $url = route('cart_count');

        JavaScript::put([
            'cart_count' => $url,
            'donate' => route('donate'),
        ]);

        if (Session::has('cart')) {

            $donor = $countries = $addresses = '';

            if (Session::has('user')) {
                $email = $request->session()->get('user')['email'];
                $donor_id = $request->session()->get('user')['donor_id'];
                /* Donor Details */
                $response = Curl::to($this->api_url . 'donor/email')
                    ->withBearer($this->token)->withOption('USERAGENT', 'DonationPortal/1.0')
                    ->withData(['email' => $email, 'donor_id' => $donor_id])
                    ->asJson()
                    ->post();

                $donor = $response->data;
                $addresses = $response->addresses;

            }

            /* Country List */
            $response = Curl::to($this->api_url . 'country')
                ->withBearer($this->token)->withOption('USERAGENT', 'DonationPortal/1.0')
                ->asJson()
                ->get();

            $countries = $response->data;

            return view('checkout', compact('donor', 'addresses', 'countries'));
        } else {
            return back();
        }
    }

    public function donate(Request $request)
    {
        \Log::info("CheckoutController donate()");
        \Log::debug(json_encode($request->all()));
        $reference_id = Str::random(32);

        // check reference id
        $response = Curl::to($this->api_url . 'payment/reference/' . $reference_id)
            ->withBearer($this->token)->withOption('USERAGENT', 'DonationPortal/1.0')
            ->asJson()
            ->get();

        info(json_encode(['payment_reference_response' => $response]));

        if ($response->success) {
            // Cart to cart transaction api call <---<<<<<
            $cart_transaction['reference_no'] = $response->reference_id;

            if (Session::has('user') && Session::has('cart')) {
                $cart_transaction['auth'] = 1;

                $cart_transaction['donor_id'] = $request->session()->get('user')['donor_id'];
                $cart_transaction['donor_address_id'] = $request->address_id ? $request->address_id : '';
            } else {
                $cart_transaction['auth'] = 0;
                $cart_transaction['session_id'] = session()->getId();

                // guest login
                $cart_transaction['guest_details'] = json_encode([
                    'title' => $request->title ? $request->title : '',
                    'first_name' => $request->first_name ? $request->first_name : '',
                    'last_name' => $request->last_name ? $request->last_name : '',
                    'email' => $request->email ? $request->email : '',
                    'phone' => $request->phone ? $request->phone : '',
                    'country' => $request->country ? $request->country : '',
                    'city' => $request->city ? $request->city : '',
                    'city_id' => $request->city_id ? $request->city_id : '',
                    'address1' => $request->address1 ? $request->address1 : '',
                    'address2' => $request->address2 ? $request->address2 : '',
                    'postcode' => $request->postcode ? strtoupper($request->postcode) : '',
                ]);

            }

            $cart_transaction['paywith'] = $request->payment_method;
            $cart_transaction['is_giftaid'] = $request->taxpayer ? $request->taxpayer : 'N';

            // opt-in details
            $cart_transaction['tele_calling'] = $request->tele_calling ? $request->tele_calling : 'N';
            $cart_transaction['send_email'] = $request->send_email ? $request->send_email : 'N';
            $cart_transaction['send_mail'] = $request->send_mail ? $request->send_mail : 'N';
            $cart_transaction['send_text'] = $request->send_text ? $request->send_text : 'N';

            // direct debit details
            $cart_transaction['dd_run'] = $request->pay_day ? $request->pay_day : '';
            $cart_transaction['bank_ac_no'] = $request->account_number ? $request->account_number : '';
            $cart_transaction['bank_sort_code'] = $request->sortcode ? $request->sortcode : '';

            $cart_transaction['reason_for_donating'] = $request->notes;

            $transactions = Curl::to($this->api_url . 'payment/transaction')
                ->withBearer($this->token)->withOption('USERAGENT', 'DonationPortal/1.0')
                ->withData($cart_transaction)
                ->asJson()
                ->post();


            if ($transactions->success) {

                // check if only direct debit
                $cart_data = Session::get('cart');
                \Log::debug(json_encode(['cart_data'=> $cart_data]));

                if (!in_array('one-off', array_column($cart_data, 'donation_period'))) {

                    return response()->json([
                        'type' => 'only-recurring',
                        'url' => route('payment.success', ['status' => 'only-direct-debit']),
                        'success' => true,
                    ]);
                }

                $data['title'] = $request->title;
                $data['phone'] = $request->phone;
                $data['email'] = $email = $request->email;
                $data['first_name'] = $request->first_name;
                $data['last_name'] = $request->last_name;
                $data['address_id'] = $request->address_id;
                $data['payment_method'] = $request->payment_method;
                $data['notes'] = $request->notes;
                $data['taxpayer'] = $request->taxpayer;
                $data['auth'] = $cart_transaction['auth'];

                $donor_session = null;
                if(Session::has('user')){
                    $donor_session = $request->session()->get('user')['donor_id'];
                }else {
                    $donor_session = session()->getId();
                }
                $total = $request->total_amount;

                if ($request->payment_method == 'stripe') {
                    return response()->json([
                        'success' => true,
                        'data' => $data,
                        'total' => $total,
                        'reference_id' => $reference_id,
                        'donor_session' => $donor_session,
                        'email' => $email,
                    ]);
                    // return view('stripeCheckoutForm', compact('total', 'data', 'reference_id', 'donor_id', 'email'));
                } else if ($request->payment_method == 'netpay') {

                    // FIXME:
                    // (new NetPayService())->payment($request, $reference_id);

                } else if ($request->payment_method == 'paypal') {

                    $redirectUrl = $this->getExpressCheckout($request, $cart_data, $reference_id);

                    return response()->json([
                        'url' => $redirectUrl,
                        'success' => true,
                        'data' => $data,
                        'total' => $total,
                        'reference_id' => $reference_id,
                        'donor_session' => $donor_session,
                        'email' => $email,
                    ]);
                }
            } else {
                return redirect()->route('home');
                // return response()->json([
                //     'success' => false,
                //     'message' => 'Something went wrong!'
                // ]);
            }

        } else {
            // return response()->json([
            //     'success' => false,
            //     'message' => 'Transaction Error. Please contact organization.'
            // ]);
            return '<p>Transaction Error. Please contact organization.</p>';
        }
    }

    public function netPayResponse(Request $request)
    {
        (new NetPayService())->response($request);
    }

    public function stripePayment()
    {
        return view('stripeCheckoutForm');
    }

}
