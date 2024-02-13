<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Ixudra\Curl\Facades\Curl;
use Session;

class NetPay
{
    protected $merchant_id;
    protected $username;
    protected $password;
    protected $encryption_key;
    protected $encryption_iv;
    protected $encryption_method;
    protected $dp_live_url;
    protected $dp_test_url;
    protected $sp_live_url;
    protected $sp_test_url;
    protected $sp_live_token_url;
    protected $sp_test_token_url;
    protected $operation_mode;

    public function __construct()
    {
        $this->setNetPayCredentials();
    }

    public function setNetPayCredentials()
    {
        $this->operation_mode = config('netpay.mode', 2); // 1: LIVE, 2: TEST

        /* YOUR MERCHANT SERVICE USER INFORMATION */
        if ($this->operation_mode == 2) {
            // DEV
            $this->merchant_id = config('netpay.sandbox.merchant'); // merchant account code
            $this->username = config('netpay.sandbox.username'); // hosted integration user name
            $this->password = config('netpay.sandbox.password'); // hosted integration password

        } else {
            // LIVE
            $this->merchant_id = config('netpay.live.merchant'); // merchant account code
            $this->username = config('netpay.live.username'); // hosted integration user name
            $this->password = config('netpay.live.password'); // hosted integration password
        }

        /* ENCRYPTION KEY and IV TO ENCRYPT INPUT DATA */
        if ($this->operation_mode == 2) {
            //DEV
            $this->encryption_key = config('netpay.sandbox.encrypt_key'); // encryption key for hosted integration
            $this->encryption_iv = config('netpay.sandbox.encrypt_iv'); // encryption iv for hosted integration
            $this->encryption_method = config('netpay.sandbox.encrypt_method', 'AES-128-CBC'); // Encryption method, IT SHOULD NOT BE CHANGED
        } else {

            // LIVE
            $this->encryption_key = config('netpay.live.encrypt_key'); // encryption key for hosted integration
            $this->encryption_iv = config('netpay.live.encrypt_iv'); // encryption iv for hosted integration
            $this->encryption_method = config('netpay.live.encrypt_method', 'AES-128-CBC'); // Encryption method, IT SHOULD NOT BE CHANGED
        }

        /* HOSTED PAYMENT FORM URLs */

        // DIRECT POST
        $this->dp_live_url = 'https://hosted.revolution.netpay.co.uk/v1/gateway/payment';
        $this->dp_test_url = 'https://hostedtest.revolution.netpay.co.uk/v1/gateway/payment';

        // SERVER POST
        $this->sp_live_url = 'https://hosted.revolution.netpay.co.uk/v1/gateway/create_payment_link';
        $this->sp_test_url = 'https://hostedtest.revolution.netpay.co.uk/v1/gateway/create_payment_link';

        // TOKEN POST
        $this->sp_live_token_url = 'https://hosted.revolution.netpay.co.uk/v1/gateway/token';
        $this->sp_test_token_url = 'https://hostedtest.revolution.netpay.co.uk/v1/gateway/token';

    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param int $reference_id
     * @param int $donor_id
     */
    public function payment($request, $reference_id)
    {
        $address1 = $address2 = $city_name = $postcode = '';
        if (Session::has('user')) {
            $response = Curl::to(config('icharms.api_url') . 'getaddress/' . $request->address_id)
                ->asJson()
                ->get();

            if ($response->success) {
                $address1 = $response->data->address1;
                $address2 = $response->data->address2;
                $city_name = $response->data->city_name;
                $postcode = $response->data->post_code;
            }
        } else {
            $address1 = $request->address1;
            $address2 = $request->address2;
            $city_name = $request->city_name;
            $postcode = $request->postcode;
        }

        $donor_id = Session::has('user') ? $request->session()->get('user')['donor_id'] : "GUEST";

        $response_url = '/response';
        $transaction_id = $this->openssl_encrypt_cbc($reference_id, $this->encryption_key, $this->encryption_iv, $this->encryption_method);

        $action_url = ($this->operation_mode == 1) ? $this->dp_live_url : $this->dp_test_url;
        $user_name = $this->openssl_encrypt_cbc($this->username, $this->encryption_key, $this->encryption_iv, $this->encryption_method);
        $password = $this->openssl_encrypt_cbc($this->password, $this->encryption_key, $this->encryption_iv, $this->encryption_method);
        $operation_mode = $this->openssl_encrypt_cbc($this->operation_mode, $this->encryption_key, $this->encryption_iv, $this->encryption_method);
        $session_token = $this->create_unique_session_token($this->merchant_id, uniqid()); // Your unique session_token. Second parameter can be your unique id

        $session_token_enc = $this->openssl_encrypt_cbc($session_token, $this->encryption_key, $this->encryption_iv, $this->encryption_method);
        $amount = $this->openssl_encrypt_cbc($request->total_amount, $this->encryption_key, $this->encryption_iv, $this->encryption_method);
        $description = $this->openssl_encrypt_cbc($reference_id . '-' . $donor_id, $this->encryption_key, $this->encryption_iv, $this->encryption_method);

        $email = $request->email == '' ? 'no-email@dummy.com' : $request->email;
        $mobile = $request->phone == '' ? '0000000000' : $request->phone;
        $bill_to_address = $this->openssl_encrypt_cbc($address1 . ' ,' . $address2, $this->encryption_key, $this->encryption_iv, $this->encryption_method);
        $bill_to_town_city = $this->openssl_encrypt_cbc($city_name, $this->encryption_key, $this->encryption_iv, $this->encryption_method);
        $bill_to_county = $this->openssl_encrypt_cbc($city_name, $this->encryption_key, $this->encryption_iv, $this->encryption_method);
        $bill_to_country = $this->openssl_encrypt_cbc('GBR', $this->encryption_key, $this->encryption_iv, $this->encryption_method);
        $bill_to_postcode = $this->openssl_encrypt_cbc($postcode, $this->encryption_key, $this->encryption_iv, $this->encryption_method);
        $customer_email = $this->openssl_encrypt_cbc($email, $this->encryption_key, $this->encryption_iv, $this->encryption_method);
        $customer_phone = $this->openssl_encrypt_cbc($mobile, $this->encryption_key, $this->encryption_iv, $this->encryption_method);
        $checksum = $this->openssl_encrypt_cbc(sha1($session_token . $request->total_amount . 'GBP' . $reference_id), $this->encryption_key, $this->encryption_iv, $this->encryption_method);

        $currency = $this->openssl_encrypt_cbc('GBP', $this->encryption_key, $this->encryption_iv, $this->encryption_method);
        $checkout_template = $this->openssl_encrypt_cbc('SWIFT', $this->encryption_key, $this->encryption_iv, $this->encryption_method);
        $response_format = $this->openssl_encrypt_cbc('JSON', $this->encryption_key, $this->encryption_iv, $this->encryption_method); // Set which data format you like to get back the resposne: JSON, XML or STR
        $response_url = $this->openssl_encrypt_cbc($this->get_url($response_url), $this->encryption_key, $this->encryption_iv, $this->encryption_method);
        $backend_response = $this->openssl_encrypt_cbc('0', $this->encryption_key, $this->encryption_iv, $this->encryption_method); // 0 : GET response only (Default), 1: GET and POST response PS: PS: if parameter is not supplied, Hosted Form will use default value
        $iframe = $this->openssl_encrypt_cbc('0', $this->encryption_key, $this->encryption_iv, $this->encryption_method); // 0: NO (Default) 1: YES,  PS: if parameter is not supplied, Hosted Form will use default value

        $data = '<body><form method="POST" name="netpay_form" action="' . $action_url . '">
        <input type="hidden" name="merchant_id" value="' . $this->merchant_id . '" />
        <input type="hidden" name="username" value="' . $user_name . '" />
        <input type="hidden" name="password" value="' . $password . '" />
        <input type="hidden" name="operation_mode" value="' . $operation_mode . '" />
        <input type="hidden" name="session_token" value="' . $session_token_enc . '" />
        <input type="hidden" name="description" value="' . $description . '" />
        <input type="hidden" name="amount" value="' . $amount . '" />
        <input type="hidden" name="currency" value="' . $currency . '" />
        <input type="hidden" name="transaction_id" value="' . $transaction_id . '" />
        <input type="hidden" name="response_url" value="' . $response_url . '" />
        <input type="hidden" name="response_format" value="' . $response_format . '" />
        <input type="hidden" name="backend_response" value="' . $backend_response . '" />
        <input type="hidden" name="iframe" value="' . $iframe . '" />
        <input type="hidden" name="checkout_template" value="' . $checkout_template . '" />
        <input type="hidden" name="bill_to_address" value="' . $bill_to_address . '" />
        <input type="hidden" name="bill_to_town_city" value="' . $bill_to_town_city . '" />
        <input type="hidden" name="bill_to_county" value="' . $bill_to_county . '" />
        <input type="hidden" name="bill_to_postcode" value="' . $bill_to_postcode . '" />
        <input type="hidden" name="bill_to_country" value="' . $bill_to_country . '" />
        <input type="hidden" name="customer_email" value="' . $customer_email . '" />
        <input type="hidden" name="customer_phone" value="' . $customer_phone . '" />
        <input type="hidden" name="checksum" value="' . $checksum . '" />
        </form><script> document.netpay_form.submit(); </script></body>';

        echo $data;
    }

    public function response(Request $request)
    {
        $resp_data = $this->openssl_decrypt_cbc($_GET['response'], $this->encryption_key, $this->encryption_iv, $this->encryption_method);
        $response = json_decode($resp_data, true);

        //dd($response);

        if ($response['result'] === 'SUCCESS') {

            // Call Donation Api
            $data['txn_id'] = $response['order_id'];
            $data['payment_amt'] = $response['amount'];
            $data['currency_code'] = $response['currency'];
            $data['auth_code'] = '';
            $data['card_txn_no'] = $response['transaction_id'];
            $data['payment_status'] = 'Completed';
            $data['reference_no'] = $response['transaction_id'];
            $data['payment_mode_code'] = 'NETPAY';

            $donor = explode("-", $response['description'])[1];

            if ($donor != 'GUEST') {
                $data['auth'] = 1;
                $data['donor_id'] = $request->session()->get('user')['donor_id'];
            } else {
                $data['auth'] = 0;
                $data['session_id'] = session()->getId();
            }

            $res = Curl::to(config('icharms.api_url') . 'payment/createSingleDonation')
                ->withData($data)
                ->asJson()
                ->post();

            /**
             *  check if donation is created
             *  if not, refund amount and reverse cart
             */
            if ($res->success) {
                $cart_data = Session::get('cart');
                if (in_array('direct-debit', array_column($cart_data, 'donation_period'))) {

                    return Redirect::route('payment.success', ['status' => 'both'])->send();
                }
                return Redirect::route('payment.success', ['status' => 'only-one-off'])->send();
            } else {
                /* reverse cart */

                Redirect::route('payment.fail')->send();
            }

        } else if ($response['result'] === 'ERROR') {

            return Redirect::route('payment.fail')->send();
        }
    }

    /**
     * Common Functions
     * */
    public function get_json_data($response)
    {
        $data = json_decode($response, true);

        return $data;
    }

    public function get_xml_data($response)
    {
        $xml = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA);
        $data = json_decode(json_encode($xml), true);

        return $data;
    }

    public function get_str_data($response)
    {
        $data = self::parse_response_url($response);

        return $data;
    }

    // Parse response string
    public function parse_response_url($response)
    {
        preg_match_all('/\{([^}]*)\}/', $response, $matches);

        $parsed_url = array();
        foreach ($matches[1] as $match) {
            list($key, $value) = explode('|', $match);
            $parsed_url[$key] = $value;
        }

        return $parsed_url;
    }

    // Format and return resposne url
    public function get_url($url)
    {
        if (isset($_SERVER['HTTPS'])) {
            $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
        } else {
            $protocol = 'http';
        }
        return $protocol . "://" . $_SERVER['HTTP_HOST'] . $url;
    }

    // Format and return CURL response
    public function format_curl_response($response, $content_type)
    {

        if (strpos($content_type, 'text/html') !== false) {
            return self::parse_response_url($response);
        } elseif (strpos($content_type, 'application/json') !== false) {
            return json_decode($response, true);
        } elseif (strpos($content_type, 'application/xml') !== false) {
            $xml = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA);
            return json_decode(json_encode($xml), true);
        }

        return null;
    }

    //change array to proper formated string
    public function product_array_to_string($products)
    {
        $prod_str = '';
        if (is_array($products)) {
            foreach ($products as $prod) {
                $prod_str .= '[';
                foreach ($prod as $key => $val) {
                    $prod_str .= "{" . "$key|$val" . "}";
                }
                $prod_str .= ']';
            }
        }

        return $prod_str;
    }

    /* Encrypt Functions */

    /*    MCRYPT ENCRYPTION
     *    MODE CBC
     */
    public function mcrypt_encrypt_cbc($input, $key, $iv)
    {
        $size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $input = integration::add_pkcs5_padding($input, $size);
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');

        mcrypt_generic_init($td, pack('H*', $key), pack('H*', $iv));
        $data = mcrypt_generic($td, $input);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        $data = bin2hex($data);

        return $data;
    }

    /*    MCRYPT DECRYPTION
     *    MODE CBC
     */
    public function mcrypt_decrypt_cbc($input, $key, $iv)
    {
        $decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, pack('H*', $key), pack('H*', $input), MCRYPT_MODE_CBC, pack('H*', $iv));

        return integration::remove_pkcs5_padding($decrypted);
    }

    /*    OPENSSL ENCRYPTION
     *    MODE CBC
     */
    public static function openssl_encrypt_cbc($input, $key, $iv, $method)
    {
        $encrypted = openssl_encrypt($input, $method, pack('H*', $key), true, pack('H*', $iv));

        $encrypted_hex = bin2hex($encrypted);

        return $encrypted_hex;
    }

    /*    OPENSSL DECRYPTION
     *    MODE CBC
     */
    public static function openssl_decrypt_cbc($input, $key, $iv, $method)
    {
        $decrypted = openssl_decrypt(pack('H*', $input), $method, pack('H*', $key), true, pack('H*', $iv));

        return $decrypted;
    }

    /*
     *    ADD PKCS5 PADDING
     */
    private function add_pkcs5_padding($text, $blocksize)
    {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    /*
     *    REMOVE PKCS5 PADDING
     */
    private function remove_pkcs5_padding($decrypted)
    {
        $dec_s = strlen($decrypted);
        $padding = ord($decrypted[$dec_s - 1]);
        $decrypted = substr($decrypted, 0, -$padding);

        return $decrypted;
    }

    /*
     *    Add timestamp to transaction id
     */
    public function create_unique_transaction_id($transaction_id)
    {
        $time = time();
        $time_1 = substr($time, 0, floor(strlen($time) / 2));
        $time_2 = substr($time, floor(strlen($time) / 2));
        $rand = '';
        $seed = str_split('abcdefghijklmnopqrstuvwxyz'
            . 'ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        foreach (array_rand($seed, 3) as $k) {
            $rand .= $seed[$k];
        }

        $unique_trans_id = strtolower($transaction_id) . $time_1 . $rand . $time_2;
        return hash('adler32', $unique_trans_id) . hash('crc32', $unique_trans_id);
    }

    /*
     *    Create token with combination of merchant_id, timestamp and transaction_id
     */
    public function create_unique_session_token($merchant_id, $transaction_id)
    {
        $time = time();
        $time_1 = substr($time, 0, floor(strlen($time) / 2));
        $time_2 = substr($time, floor(strlen($time) / 2));
        $rand = '';
        $seed = str_split('abcdefghijklmnopqrstuvwxyz' . 'ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        foreach (array_rand($seed, 3) as $k) {
            $rand .= $seed[$k];
        }

        return substr(strtolower($merchant_id) . $time_1 . $rand . $time_2 . strtolower($transaction_id), 0, 25);
    }

}
