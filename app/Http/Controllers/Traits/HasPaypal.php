<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Facades\Session;
use Srmklive\PayPal\Services\ExpressCheckout;

trait HasPaypal
{
    protected $provider;

    public function __construct()
    {
        parent::__construct();
        $this->provider = new ExpressCheckout();
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function getExpressCheckout(Request $request, $cart_data, $reference_id)
    {
        info('HasPaypal getExpressCheckout(Request $request, $cart_data, $reference_id)');
        info(json_encode([$request->all(), $cart_data, $reference_id]));

        $guest = true;

        if (Session::has('user') && Session::has('cart')) {
            $donor_session_id = $request->session()->get('user')['donor_id'];
            $guest = false;
        }else{
            $donor_session_id = session()->getId();
        }

        $recurring = ($request->get('mode') === 'recurring') ? true : false;
        $cart = $this->getCheckoutData($cart_data, $reference_id, $donor_session_id, $guest);

        try {
            $response = $this->provider->setExpressCheckout($cart, $recurring, true);

            info(json_encode(['express_chkout_res' => $response]));
            // header("Location: " . $response['paypal_link']);
            return $response['paypal_link'];

        } catch (\Exception $e) {
            info($e->getMessage());
            session()->put(['code' => 'danger', 'message' => "Error processing PayPal payment for Order $invoice->id!"]);
        }
    }

    /**
     * Set cart data for processing payment on PayPal.
     *
     * @param bool $recurring
     *
     * @return array
     */
    protected function getCheckoutData($cart_data, $reference_id, $donor_session_id, $guest = true)
    {
        $data = [];
        $total = 0;

        foreach ($cart_data as $key => $value) {
            if ($value->donation_period == 'one-off') {
                $data['items'][$key]['name'] = $value->category_name;
                $data['items'][$key]['price'] = $value->donation_amount;
                $data['items'][$key]['qty'] = $value->quantity;

                $total += $value->donation_amount * $value->quantity;
            }
        }

        $data['return_url'] = route('paypal.checkout-success');
        $data['cancel_url'] = route('home');

        $data['invoice_id'] = config('paypal.invoice_prefix') . '||' . $reference_id . '||' . $donor_session_id . '||' . $guest;
        $data['invoice_description'] = "Donation through Website. Order # $reference_id";

        $data['total'] = $total;

        Session::put('cart', $data);
        return $data;
    }

    /**
     * Process payment on PayPal.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getExpressCheckoutSuccess(Request $request)
    {
        info('HasPaypal getExpressCheckoutSuccess(Request $request)');
        info(json_encode([$request->all()]));

        $recurring = ($request->get('mode') === 'recurring') ? true : false;
        $token = $request->get('token');
        $PayerID = $request->get('PayerID');
        $cart_data = Session::get('cart');
        $user = Session::get('user');

        // Verify Express Checkout Token
        $response = $this->provider->getExpressCheckoutDetails($token);

        $res = explode('||', $response['INVNUM']);
        $reference_id = $res[1];
        $donor_id = $res[2];

        // $cart = $this->getCheckoutData($cart_data, $reference_id, $donor_id);
        info(json_encode(['cart' => $cart_data, 'user' => $user]));

        if (in_array(strtoupper($response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])) {

            if ($recurring === true) {

                /* FIXME: */
                $response = $this->provider->createMonthlySubscription($response['TOKEN'], 9.99, $cart_data['subscription_desc']);
                if (!empty($response['PROFILESTATUS']) && in_array($response['PROFILESTATUS'], ['ActiveProfile', 'PendingProfile'])) {
                    $status = 'Processed';
                } else {
                    $status = 'Invalid';
                }
            } else {
                // Perform transaction on PayPal
                $payment_status = $this->provider->doExpressCheckoutPayment($cart_data, $token, $PayerID);
                info(json_encode(['payment_status' => $payment_status]));
                $status = $payment_status['PAYMENTINFO_0_PAYMENTSTATUS'];
            }

            $data['txn_id'] = $payment_status['PAYMENTINFO_0_TRANSACTIONID'];
            $data['payment_amt'] = $payment_status['PAYMENTINFO_0_AMT'];
            $data['currency_code'] = $payment_status['PAYMENTINFO_0_CURRENCYCODE'];
            $data['auth_code'] = '';
            $data['status'] = $status;
            $data['card_txn_no'] = $payment_status['PAYMENTINFO_0_TRANSACTIONID'];
            $data['payment_status'] = $status;
            $data['reference_no'] = $reference_id;
            $data['donor_id'] = $donor_id;
            $data['payment_mode_code'] = 'PAYPAL';

            if ($user) {
                $data['auth'] = 1;
                $data['donor_id'] = $user['donor_id'];
            } else {
                $data['auth'] = 0;
                $data['session_id'] = session()->getId();
            }

            $data['receiver_email'] = $payment_status['PAYMENTINFO_0_SELLERPAYPALACCOUNTID'];

            $invoice = $this->createInvoice($data, $status);

            if ($invoice->success) {

                if (in_array('direct-debit', array_column($cart_data, 'donation_period'))) {
                    $status = 'both';
                } else {
                    $status = 'only-one-off';
                }

                // return redirect()->route('payment.success', $status);
                return redirect()->route('payment.success', ['status' => $status,'payment' => $data['payment_amt'], 'ref' => $data['card_txn_no'], 'donor_name' => $invoice->donor_name]);

            } else {
                /* TODO:  */
                return redirect()->route('payment.fail');
            }
        }
    }

    /**
     * Parse PayPal IPN.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function notify(Request $request)
    {
        Log::debug($request->all());
        if (!($this->provider instanceof ExpressCheckout)) {
            $this->provider = new ExpressCheckout();
        }

        $post = [
            'cmd' => '_notify-validate',
        ];
        $data = $request->all();

        foreach ($data as $key => $value) {
            $post[$key] = $value;
        }

        $response = (string) $this->provider->verifyIPN($post);

        // $ipn = new IPNStatus();
        // $ipn->payload = json_encode($post);
        // $ipn->status = $response;
        // $ipn->save();
    }

    public function paypalIpn()
    {
        // $listener = new IpnListener();
        $ipn = new PaypalIPNListener();
        $ipn->use_sandbox = true;
        // $ipn->force_ssl_v3 = true;
        // $ipn->use_curl = false;

        $verified = $ipn->processIpn();

        $report = $ipn->getTextReport();

        // Log::info("-----new payment-----");

        Log::info($report);

        if ($verified) {
            if ($_POST['address_status'] == 'confirmed') {
                // Check outh POST variable and insert your logic here
                Log::info("payment verified and inserted to db");
            }
        } else {
            Log::info("Some thing went wrong in the payment !");
        }
    }

    protected function createInvoice($data, $status)
    {
        info("Creating invoice");
        info("status: $status");
        info(json_encode($data));

        if (!strcasecmp($status, 'Completed') || !strcasecmp($status, 'Processed') || !strcasecmp($status, 'Pending')) {

            $transactions = Curl::to($this->api_url . 'payment/create-single-donation')
                ->withBearer($this->token)->withOption('USERAGENT', 'DonationPortal/1.0')
                ->withData($data)
                ->asJson()->post();
        } else {

            $transactions = Curl::to($this->api_url . 'payment/transaction')
                ->withBearer($this->token)->withOption('USERAGENT', 'DonationPortal/1.0')
                ->withData($data)
                ->asJson()->post();
        }

        info(json_encode(['transactions' => $transactions]));
        return $transactions;
    }
}
