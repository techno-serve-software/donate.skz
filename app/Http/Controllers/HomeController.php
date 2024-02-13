<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;
use JavaScript;
use Session;

class HomeController extends Controller
{
    use fetchCart;

    public function donation()
    {
        return redirect()->to('/');
    }

    public function index(Request $request)
    {
        \Log::info("HomeController index()");

        $this->fetchCartData($request);
        $url = route('cart_count');

        JavaScript::put([
            'cart_count' => $url,
        ]);

        $categories = Curl::to($this->api_url . 'category')
            ->withBearer($this->token)->withOption('USERAGENT', 'DonationPortal/1.0')
        // ->enableDebug('./storage/logs/curl.log')
            ->asJson()
            ->get();

        // \Log::info('Categories list');
        // \Log::info(json_encode($categories));

        return view('home', compact('categories'));
    }

    public function qurbaniPage(Request $request)
    {
        $this->fetchCartData($request);
        $url = route('cart_count');

        JavaScript::put([
            'cart_count' => $url,
        ]);

        $category_id = 5; /* Qurbani Category Id */

        $countries = Curl::to($this->api_url . 'category/country/' . $category_id)
            ->withBearer($this->token)->withOption('USERAGENT', 'DonationPortal/1.0')
            ->asJson()->get();
        // ->enableDebug('log.txt')

        // dd($countries);
        return view('qurbani', compact('countries'));
    }

    public function fetchQurbaniPrograms(Request $request)
    {
        $country_id = $request->country_id;
        $category_id = $request->category_id;

        $programsWithRate = Curl::to($this->api_url . 'programs-with-rate/' . $category_id . '/' . $country_id)
            ->withBearer($this->token)->withOption('USERAGENT', 'DonationPortal/1.0')
            ->asJson()->get();

        if ($programsWithRate) {

            // create html element for program

            $opt = '<label for="programs">Programs <span class="required">*</span></label>
                <div class="rbox-wrap" data-error-container="program-error">';
            $i = 0;
            foreach ($programsWithRate->qurbani_program_rate as $program) {
                if ($program->program_id == 1) {
                    $program_name = 'Goat';

                    $animal_image = 'goat.png';
                }
                // else if ($program->program_id == 2) {
                //     $program_name = 'Sheep';
                // }
                else if ($program->program_id == 2) {
                    $program_name = '1/7th Cow/Bull';
                    $animal_image = 'cow.png';
                } else {
                    $program_name = '1/7th Camel';
                    $animal_image = 'camel.png';
                }

                $checked = ($i < 1) ? 'checked="checked"' : '';

                $image_url = url('assets/images/animals/' . $animal_image);

                $opt .= '<div class="rbox">
                        <input type="radio" id="p' . ++$i . '" ' . $checked . ' data-rate="' . $program->program_rate . '" name="programs" class="programs" value="' . $program->program_id . '">
                        <label for="p' . $i . '" class="p' . $i . '"> ' . $program_name . ' <br> <img src="' . $image_url . '" alt="' . $program_name . '"> <br> <i class="fa fa-gbp"></i> ' . $program->program_rate . ' </label>
                    </div>';
            }
            $opt .= '<div class="clearfix"></div><div id="#program-error"></div></div>';

            return response()->json([
                'html' => $opt,
                'success' => true,
            ]);
        } else {
            return response()->json([
                'success' => false,
            ]);
        }
    }

    public function programs(Request $request)
    {
        $category_id = $request->category_id;

        $programs = Curl::to($this->api_url . 'program/' . $category_id)
            ->withBearer($this->token)->withOption('USERAGENT', 'DonationPortal/1.0')
            ->asJson()->get();

        if ($programs) {
            // create html element for program
            $opt = '<label for="programs">Programs <span class="required">*</span></label>

            <select id="programs" name="programs" class="form-control selectpicker" data-live-search="true">

            <option value="">Select Program</option>';
            foreach ($programs->program as $program) {
                $opt .= '<option data-participant-required="' . $program->participant_required . '" value="' . $program->program_id . '">' . $program->program_name . '</option>';
            }

            $opt .= '</select>';
            return $opt;
        } else {
            return response()->json([
                'success' => false,
            ]);
        }
    }

    public function program_country(Request $request)
    {
        $program_id = $request->program_id;
        $participant_required = $request->participant_required;

        $countries = Curl::to($this->api_url . 'country/' . $program_id)
            ->withBearer($this->token)->withOption('USERAGENT', 'DonationPortal/1.0')
            ->asJson()->get();

        /**
         * If program country rate is not defined
         * show other amount field and
         * set country to MNA (Id: 19)
         */

        if ($countries) {
            if ($participant_required == 'N') {
                if ($countries->country) {

                    $opt = '<label for="country">Country <span class="required">*</span></label>

                        <select id="country" name="country" class="form-control selectpicker">';

                    foreach ($countries->country as $country) {
                        // NOTE: default country set to PAK
                        $selected = $country->country_id == '2' ? 'selected="selected"' : '';
                        $opt .= '<option '. $selected .' value="' . $country->country_id . '">' . $country->country_name . '</option>';
                    }

                    $opt .= '</select>';

                    return response()->json(['country_input' => $opt, 'other_input_box' => 'N']);
                } else {
                    $amount_box = '
                    <div class="rbox2">
                    <input type="radio" id="r1" checked name="camount" class="camount" value="5.00">
                    <label for="r1">5.00 GBP</label>
                    </div>';
                    $amount_box .= '
                    <div class="rbox2">
                    <input type="radio" id="r2" name="camount" class="camount" value="10.00">
                    <label for="r2">10.00 GBP</label>
                    </div>';
                    $amount_box .= '
                    <div class="rbox2">
                    <input type="radio" id="r3" name="camount" class="camount" value="15.00">
                    <label for="r3">15.00 GBP</label>
                    </div>';
                    $country_mna = '<input type="hidden" value="19" name="country">';
                    return response()->json(['amount_box' => $amount_box, 'country_input' => $country_mna, 'other_input_box' => 'Y']);
                }
            } else {
                if ($countries->country) {

                    $opt = '<label for="country">Country <span class="required">*</span></label>

                        <select id="country" name="country" class="form-control selectpicker">

                    ';
                    foreach ($countries->country as $country) {
                        // NOTE: default country set to PAK
                        $selected = $country->country_id == '2' ? 'selected="selected"' : '';
                        $opt .= '<option '. $selected .' value="' . $country->country_id . '">' . $country->country_name . '</option>';
                    }

                    $opt .= '</select>';

                    return response()->json(['country_input' => $opt, 'other_input_box' => 'N']);
                } else {

                    $opt = 'No Country Assigned. Contact Organization';

                    return response()->json(['country_input' => $opt, 'other_input_box' => 'N']);
                }
            }
        } else {
            return response()->json([
                'success' => false,
            ]);
        }

    }

    public function program_rate(Request $request)
    {
        $country_id = $request->country_id;
        $program_id = $request->program_id;
        $participant_required = $request->participant_required;

        $program_rates = Curl::to($this->api_url . 'program-rate/' . $program_id . '/' . $country_id)
            ->withBearer($this->token)->withOption('USERAGENT', 'DonationPortal/1.0')
            ->asJson()
            ->get();

        $amount_box = '';
        if ($program_rates->success) {
            if ($participant_required == 'Y') {
                return response()->json(['program_rate' => $program_rates->program_rate->program_rate, 'participant' => 'Y']);
            } else {
                $amount_box .= '
            <div class="rbox2">
            <input type="radio" id="r1" checked name="camount" class="camount" value="' . $program_rates->program_rate->program_rate . '">
            <label for="r1">' . $program_rates->program_rate->program_rate . ' GBP</label>
            </div>';
                $amount_box .= '
            <div class="rbox2">
            <input type="radio" id="r2" name="camount" class="camount" value="' . number_format($program_rates->program_rate->program_rate * 2, 2) . '">
            <label for="r2">' . number_format($program_rates->program_rate->program_rate * 2, 2) . ' GBP</label>
            </div>';
                $amount_box .= '
            <div class="rbox2">
            <input type="radio" id="r3" name="camount" class="camount" value="' . number_format($program_rates->program_rate->program_rate * 4, 2) . '">
            <label for="r3">' . number_format($program_rates->program_rate->program_rate * 4, 2) . ' GBP</label>
            </div>';

                return response()->json(['html' => $amount_box, 'other_input_box' => $program_rates->program_rate->any_amount, 'participant' => 'N']);
            }
        }

    }

    public function addToCart(Request $request)
    {
        // dd($request->all());
        if ($request->camount) {

            $amount = $request->camount;
        } elseif ($request->participant_camount) {

            $amount = $request->participant_camount;
        } else {
            $amount = $request->custom_amount;
        }

        $amount = floatval(preg_replace('/[^\d.]/', '', $amount));
        \Log::info("amount => $amount");

        $qty = 1;
        $names = '';
        if ($request->participant_name) {
            $names = implode(",", $request->participant_name);
            $qty = count($request->participant_name);
        }

        $item = array(
            'donation_period' => $request->payment_method == 'O' ? 'one-off' : 'direct-debit',
            'category_id' => $request->donation_type,
            'program_id' => $request->programs,
            'country_id' => $request->country,
            'quantity' => $qty,
            'participant_name' => $names,

            'program_rate' => $amount,
            'donation_amount' => $amount,
            'donation_pound_amount' => $amount,

            'currency' => 'GBP',
            'currency_id' => 1,

            /* Not required */
            'isOther' => '',
            'orphan_id' => '',
            'orphan_name' => '',
        );

        if ($request->session()->has('user')) {

            $item['donor_id'] = $request->session()->get('user')['donor_id'];
            $item['session_id'] = '';
        } else {
            $item['session_id'] = session()->getId();
            $item['donor_id'] = '';
        }

        $cart_data = Curl::to($this->api_url . 'cart/create')
            ->withBearer($this->token)->withOption('USERAGENT', 'DonationPortal/1.0')
            ->withData($item)
            ->asJson(true)
            ->post();

        $this->fetchCartData($request);
        return response()->json([
            'success' => true,
        ]);
    }

    // BEGIN : FUNCTION TO COUNT CART ITEMS
    public function itemCount(Request $request)
    {
        if ($request->session()->has('cart')) {
            echo count($request->session()->get('cart'));
        } else {
            echo 0;
        }
    }
    // END OF item_count()

    public function total(Request $request)
    {
        $this->fetchCartData($request);
        if ($request->session()->has('cart')) {
            $items = $request->session()->get('cart');
            $donation_single_total = $donation_monthly_total = 0;

            foreach ($items as $item) {
                if ($item->donation_period == 'one-off') {
                    $donation_single_total += $item->donation_amount * $item->quantity;
                } else {
                    $donation_monthly_total += $item->donation_amount * $item->quantity;
                }

            }
            return [
                'single' => number_format($donation_single_total, 2),
                'monthly' => number_format($donation_monthly_total, 2),
            ];
        } else {
            return false;
        }
    }

    public function updateQuantity(Request $request)
    {
        $item['cart_id'] = $request->cart_id;
        if ($request->session()->has('users')) {
            $item['donor_id'] = $request->session()->get('user')['donor_id'];
            $item['session_id'] = '';
        } else {
            $item['session_id'] = session()->getId();
            $item['donor_id'] = '';
        }

        $item['quantity'] = $request->quantity;

        $response = Curl::to($this->api_url . 'cart/quantity')
            ->withBearer($this->token)->withOption('USERAGENT', 'DonationPortal/1.0')
            ->withData($item)
            ->asJson()
            ->post();
        if ($response->success) {

            $totals = $this->total($request);

            if ($totals) {
                $single_total = $totals['single'];
                $monthly_total = $totals['monthly'];
            }

            return response()->json([
                'success' => true,
                'one_off_total' => $single_total,
                'monthly_total' => $monthly_total,
            ]);
        } else {
            return response()->json([
                'success' => false,
            ]);
        }
    }

    public function deleteItem(Request $request)
    {
        $item['cart_id'] = $request->cart_id;
        if ($request->session()->has('users')) {
            $item['donor_id'] = $request->session()->get('user')['donor_id'];
            $item['session_id'] = '';
        } else {
            $item['session_id'] = session()->getId();
            $item['donor_id'] = '';
        }

        $response = Curl::to($this->api_url . 'cart/delete')
            ->withBearer($this->token)->withOption('USERAGENT', 'DonationPortal/1.0')
            ->withData($item)
            ->asJson()
            ->post();

        if ($response->success) {

            $totals = $this->total($request);
            $single_total = $monthly_total = 0;
            if ($totals) {
                $single_total = $totals['single'];
                $monthly_total = $totals['monthly'];
            }

            return response()->json([
                'success' => true,
                'one_off_total' => $single_total,
                'monthly_total' => $monthly_total,
            ]);
        } else {
            return response()->json([
                'success' => false,
            ]);
        }
    }

    public function paymentSuccess(Request $request)
    {
        $this->fetchCartData($request);
        $url = route('cart_count');

        JavaScript::put([
            'cart_count' => $url,
        ]);

        return view('payment.success');

    }

    public function paymentFailure(Request $request)
    {
        $this->fetchCartData($request);
        $url = route('cart_count');

        JavaScript::put([
            'cart_count' => $url,
        ]);

        return view('payment.fail');
    }

    public function paymentCancel(Request $request)
    {
        $this->fetchCartData($request);
        $url = route('cart_count');

        JavaScript::put([
            'cart_count' => $url,
        ]);

        return view('payment.cancel');
    }
}
