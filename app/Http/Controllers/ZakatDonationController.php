<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Ixudra\Curl\Facades\Curl;

class ZakatDonationController extends Controller
{
    public function __invoke(Request $request)
    {
        Log::info("QuickDonationController");
        Log::info(json_encode($request->all()));

        // TODO: validate

        // https://sadaqaonline.org/donation/quick-donate?amount=100
        // $requestHost = parse_url($request->headers->get('origin'),  PHP_URL_HOST);
        // Log::info(json_encode($requestHost));
        // request --> category, program id, amount

        $amount = $request->amount;
        $amount = floatval(preg_replace('/[^\d.]/', '', $amount));
        Log::info("amount => $amount");

        $item = array(
            'donation_period' => 'one-off',
            'category_id' => 4,
            'program_id' => 44,
            'country_id' => 2, /* PAK */
            'quantity' => 1,
            'participant_name' => "",
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

        // refirect to checkout page
        return redirect()->route('checkout');

    }
}
