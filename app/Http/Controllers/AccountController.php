<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;
use JavaScript;
use Session;
use SimpleCurl;

class AccountController extends Controller
{

    public function ddPaymentDetails(Request $request)
    {

        $direct_debit_ref = $request->direct_debit_ref;

        $ddPaymentDetail = Curl::to($this->api_url . 'donor/directDebitPayment/' . $direct_debit_ref)
            ->withBearer($this->token)->withOption('USERAGENT', 'DonationPortal/1.0')
            ->asJson()
            ->get();
        $dd_payment_detail = '';

        if ($ddPaymentDetail) {
            foreach ($ddPaymentDetail->payment_details as $directDebit) {
                $dd_payment_detail .= '<tr>
                                                <td>' . \Carbon\Carbon::parse($directDebit->dd_ledger_date)->format('d F, Y') . '</td>
                                                <td> <i class="fa fa-gbp"></i> ' . $directDebit->dd_ledger_received . '</td>
                                                <td>';
                if ($directDebit->dd_ledger_received == 0) {
                    $dd_payment_detail .= '<span class="badge badge-primary">Received</span>';
                } else {
                    $dd_payment_detail .= '<span class="badge badge-danger">Failed</span>';
                }
                $dd_payment_detail .= '</td></tr>';
            }

        }

        return $dd_payment_detail;
    }

    public function totalDonation($donor_id)
    {
        \Log::info("AccountController totalDonation($donor_id)");

        $totalDonation = Curl::to($this->api_url . 'donor/total-donation/' . $donor_id)
            ->withBearer($this->token)->withOption('USERAGENT', 'DonationPortal/1.0')
            ->asJson()
            ->get();

        return $totalDonation->data;
    }

    public function totalDD($donor_id)
    {
        $totalDD = Curl::to($this->api_url . 'donor/total-dd/' . $donor_id)
            ->withBearer($this->token)->withOption('USERAGENT', 'DonationPortal/1.0')
            ->asJson()
            ->get();

        return $totalDD->data->total_records;
    }

    public function profile(Request $request)
    {
        if (Session::has('user')) {

            $url = route('cart_count');

            JavaScript::put([
                'cart_count' => $url,
            ]);

            $title = 'My Profile';
            $donor_id = $request->session()->get('user')['donor_id'];

            /* Donor Details */
            $response = Curl::to($this->api_url . 'donor/detail/' . $donor_id)
                ->withBearer($this->token)->withOption('USERAGENT', 'DonationPortal/1.0')
                ->asJson()
                ->get();

            $donor = $response->data;

            /* Country List */
            $response = Curl::to($this->api_url . 'country')
                ->withBearer($this->token)->withOption('USERAGENT', 'DonationPortal/1.0')
                ->asJson()
                ->get();

            $countries = $response->data;
            //Total Donation for menu
            $totalDonation = $this->totalDonation($donor_id);
            //Total DD for menu
            $totalDD = $this->totalDD($donor_id);
            return view('account.profile', compact('title', 'donor', 'countries', 'totalDonation', 'totalDD'));
        } else {
            return back();
        }
    }
    public function changePassword(Request $request){
        if(Session::has('user')){
            $url =route('cart_count');
            JavaScript::put([
                'cart_count' => $url,
            ]);
            $title = 'Change Password';

            return view('account.changePassword', compact('title'));
        }
        else{
            return back();
        }
    }
    public function address(Request $request)
    {
        if (Session::has('user')) {

            $url = route('cart_count');

            JavaScript::put([
                'cart_count' => $url,
            ]);

            $title = 'Address';
            $donor_id = $request->session()->get('user')['donor_id'];

            /* Donor Details */
            $response = Curl::to($this->api_url . 'donor/address/' . $donor_id)
                ->withBearer($this->token)->withOption('USERAGENT', 'DonationPortal/1.0')
                ->asJson()
                ->get();
            $address = $response->data;
            // dd($address);

            /* Country List */
            $response = Curl::to($this->api_url . 'country')
                ->withBearer($this->token)->withOption('USERAGENT', 'DonationPortal/1.0')
                ->asJson()
                ->get();

            $countries = $response->data;
            //Total Donation for menu
            $totalDonation = $this->totalDonation($donor_id);
            //Total DD for menu
            $totalDD = $this->totalDD($donor_id);
            return view('account.address', compact('title', 'address', 'countries', 'totalDonation', 'totalDD'));
        } else {
            return back();
        }
    }
    public function updatePassword(Request $request)
    {
        $donor_id = $request->session()->get('user')['donor_id'];
        /* Donor Details */
        $data = array(
            'donor_id' => $donor_id,
            'password' => $request->new_password,
        );
        \Log::info(json_encode($data));
        $response = Curl::to($this->api_url . 'donor/update-donor-password')
            ->withBearer($this->token)->withOption('USERAGENT', 'DonationPortal/1.0')
            ->withData($data)
            ->asJson()
            ->post();
            if ($response->success) {

            return response()->json([
                'success' => true,
                'message' => 'Updated Successfully',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry! Nothing Updated.',
            ]);
        }
    }
    public function updateDonorData(Request $request)
    {
        /* Donor Details */
        $data = array(
            'donor_id' => $request->donor_id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'title' => $request->title,
        );
        $response = Curl::to($this->api_url . 'donor/update-donor')
            ->withBearer($this->token)->withOption('USERAGENT', 'DonationPortal/1.0')
            ->withData($data)
            ->asJson()
            ->post();
        // dd($response);
        if ($response->success) {
            return response()->json([
                'success' => true,
                'message' => 'Updated Successfully',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry! Nothing Updated.',
            ]);
        }
    }

    public function donation(Request $request)
    {
        if (Session::has('user')) {

            $url = route('cart_count');

            $title = 'My Donation';
            $donor_id = $request->session()->get('user')['donor_id'];

            //Total Donation for menu
            $totalDonation = $this->totalDonation($donor_id);

            //Total DD for menu
            $totalDD = $this->totalDD($donor_id);

            $data['donor_id'] = $donor_id; //1784

            $data['order_by'] = isset($request->orderby) ? $request->orderby : 'asc';
            $data['order_value'] = isset($request->sortValue) ? $request->sortValue : 'donation_date';
            $data['page'] = isset($request->page) ? $request->page : '';

            $headers = ["Authorization: Bearer $this->token"];

            $donations = SimpleCurl::post($this->api_url . 'donor/one-off-transaction', $data, $headers)
                ->getPaginatedResponse();

            JavaScript::put([
                'cart_count' => $url,
                'current_page' => $donations->currentPage(),
            ]);

            return view('account.donation', compact('title', 'totalDonation', 'totalDD', 'donations'));

        } else {
            return back();
        }
    }

    public function direct_debit(Request $request)
    {
        if (Session::has('user')) {
            $url = route('cart_count');

            $title = 'My Direct Debit';

            $donor_id = $request->session()->get('user')['donor_id'];

            //Total Donation for menu
            $totalDonation = $this->totalDonation($donor_id);

            //Total DD for menu
            $totalDD = $this->totalDD($donor_id);

            $data['donor_id'] = $donor_id; //99092

            $data['order_by'] = isset($request->orderby) ? $request->orderby : 'asc';
            $data['order_value'] = isset($request->sortValue) ? $request->sortValue : 'auddis_date';
            $data['page'] = isset($request->page) ? $request->page : '';

            $headers = ["Authorization: Bearer $this->token"];

            $directDebits = SimpleCurl::post($this->api_url . 'donor/direct-debit-transaction', $data, $headers)
                ->getPaginatedResponse();
            JavaScript::put([
                'cart_count' => $url,
                'current_page' => $directDebits->currentPage(),
            ]);

            return view('account.directdebit', compact('title', 'totalDonation', 'totalDD', 'directDebits'));
        } else {
            return back();
        }
    }
}
