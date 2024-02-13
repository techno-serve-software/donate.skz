<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;
use Session;
use JavaScript;

class LoginController extends Controller
{
    use fetchCart;

    public function login(Request $request)
    {
        $session_id = session()->getId();
        $response = Curl::to($this->api_url . 'login')
        ->withBearer($this->token)->withOption('USERAGENT', 'DonationPortal/1.0')
            ->withData(array('user_email' => $request->user_email, 'user_password' => $request->user_password, 'session_id' => $session_id))
            ->asJson()
            ->post();

        if ($response->success) {

            $sess_array = array(
                'donor_id' => $response->donor->user_id,
                'email' => $response->donor->user_email,
                'first_name' => $response->donor->first_name,
            );

            $request->session()->put('user', $sess_array);
            $this->fetchCartData($request);

            return response()->json([
                'success' => true,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => $response->message,
            ]);
        }
    }

    public function signup(Request $request)
    {
        $data['user_email'] = $request->user_email;
        $data['user_password'] = $request->user_password;
        $data['first_name'] = $request->first_name;
        $data['last_name'] = $request->last_name;
        // print_r($data);
        // die();
        $response = Curl::to($this->api_url . 'signup')
        ->withBearer($this->token)->withOption('USERAGENT', 'DonationPortal/1.0')
            ->withData($data)
            ->asJson()
            ->post();
        // dd($response);
        if ($response->success) {

            $sess_array = array(
                'donor_id' => $response->donor->user_id,
                'email' => $response->donor->user_email,
                'first_name' => $response->donor->first_name,
            );
            // dd($sess_array);

            $request->session()->put('user', $sess_array);
            $this->fetchCartData($request);

            return response()->json([
                'success' => true,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => $response->message,
            ]);
        }
    }

    public function resetPage(Request $request)
    {
        $url = route('cart_count');

        JavaScript::put([
            'cart_count' => $url,
        ]);

        $token = $request->token;
        return view('resetPassword', compact('token'));
    }

    public function resetToken(Request $request)
    {
        $response = Curl::to($this->api_url . 'forget-password')
        ->withBearer($this->token)->withOption('USERAGENT', 'DonationPortal/1.0')
            ->withData(array('user_email' => $request->user_email))
            ->asJson()
            ->post();

        if ($response->success) {
            return response()->json([
                'success' => true,
                'message' => $response->message,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => $response->message,
            ]);
        }
    }

    public function resetPassword(Request $request)
    {
        $response = Curl::to($this->api_url . 'reset-password')
        ->withBearer($this->token)->withOption('USERAGENT', 'DonationPortal/1.0')
            ->withData(array('token' => $request->token, 'user_password' => $request->user_password))
            ->asJson()
            ->post();

        // dd($response);
        if ($response->success) {
            return response()->json([
                'success' => true,
                'message' => $response->message,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => $response->message,
            ]);
        }
    }

    public function logout(Request $request)
    {
        // remove user session data
        $request->session()->flush();

        return redirect()->route('home');
    }

}
