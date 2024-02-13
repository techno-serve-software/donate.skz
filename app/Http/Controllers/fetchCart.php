<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;
use Session;

trait fetchCart
{
    public function fetchCartData(Request $request, $cart_data = null)
    {
        if (!$cart_data) {
            $cart = array();
            if ($request->session()->has('user')) {
                $cart['donor_id'] = $request->session()->get('user')['donor_id'];
                $cart['session_id'] = '';
            } else {
                $cart['session_id'] = session()->getId();
                $cart['donor_id'] = '';
            }

            $api_url = config('icharms.api_url');
            $cart_data = Curl::to($api_url . 'cart/cart')
                ->withBearer($this->token)->withOption('USERAGENT', 'DonationPortal/1.0')
                ->withData($cart)
                ->asJson()
                ->post();

        }

        \Log::info("cart data");
        \Log::info(json_encode($cart_data));

        if (!empty($cart_data->cart)) {
            // put item to session
            $request->session()->put('cart', $cart_data->cart);
        } else {
            \Log::info('Cart Empty');
            \Log::info(session()->getId());
            $request->session()->forget('cart');
        }
    }
}
