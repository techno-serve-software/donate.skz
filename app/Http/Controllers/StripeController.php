<?php

namespace App\Http\Controllers;

use App\Services\Stripe as StripeService;
use Cartalyst\Stripe\Stripe;
use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;
use Log;
use Session;
use Validator;

class StripeController extends Controller
{
    /**
     * Redirect the user to the Payment Gateway.
     *
     */
    public function stripe(Request $request)
    {
        return view('stripeCheckoutForm');
    }

    /**
     * Redirect the user to the Payment Gateway.
     *
     */
    public function payStripeOld(Request $request)
    {
        $stripe = Stripe::make(config('stripe.secret'));

        try {
            $charge = $stripe->charges()->create([
                'source' => $request->stripeToken,
                'currency' => 'GBP',
                'amount' => $request->amount,
                'description' => 'Payment through AST Donation Website',
                'receipt_email' => $request->email,
                'metadata' => [

                ],
            ]);

            if ($charge['status'] == 'succeeded') {

                // Call Donation Api
                $data['txn_id'] = $charge['id'];
                $data['payment_amt'] = $charge['amount'] / 100;
                $data['currency_code'] = strtoupper($charge['currency']);
                $data['auth_code'] = '';
                $data['card_txn_no'] = $charge['id'];
                $data['payment_status'] = 'Completed';
                $data['reference_no'] = $request->reference_id;
                $data['payment_mode_code'] = 'STRIPE';

                if ($request->_auth) {
                    $data['auth'] = 1;
                    $data['donor_id'] = $request->session()->get('user')['donor_id'];
                } else {
                    $data['auth'] = 0;
                    $data['session_id'] = session()->getId();
                }

                $response = Curl::to($this->api_url . 'payment/create-single-donation')
                    ->withBearer($this->token)->withOption('USERAGENT', 'DonationPortal/1.0')
                    ->withData($data)
                    ->asJson()
                    ->post();

                /**
                 *  check if donation is created
                 *  if not, refund amount and reverse cart
                 */
                if ($response->success) {
                    $cart_data = Session::get('cart');
                    if (in_array('direct-debit', array_column($cart_data, 'donation_period'))) {

                        return redirect()->route('payment.success', ['status' => 'both']);
                    }
                    return redirect()->route('payment.success', ['status' => 'only-one-off']);
                } else {
                    /* reverse cart */
                    $stripe->refunds()->create($charge['id']);

                    return redirect()->route('payment.fail');
                }

            } else {

                /* reverse cart */
                /* TODO: */
                return redirect()->route('payment.fail');
            }
        } catch (\Exception $ex) {
            Log::debug($ex);
            return $ex->getMessage();

        }

    }

    /**
     * Get All Data from this method.
     *
     */
    public function webhook()
    {
        //include Stripe PHP library
        // require_once APPPATH . "third_party/stripe-php-7.67.0/init.php";

        \Stripe\Stripe::setApiKey(config('stripe.secret'));

        $payload = @file_get_contents('php://input');
        $event = null;
        try {
            $event = \Stripe\Event::constructFrom(
                json_decode($payload, true)
            );
        } catch (\UnexpectedValueException $e) {

            // Invalid payload

            //echo '⚠️  Webhook error while parsing basic request.';
            Log::debug('⚠️  Webhook error while parsing basic request.');
            http_response_code(400);
            exit();
        }

        // Handle the event

        switch ($event->type) {
            case 'charge.refunded':

                $paymentRefund = $event->data->object; // contains a \Stripe\PaymentIntent
                $refundedAmount = $paymentRefund->refunds->data[0]->amount / 100;
                $capturedAmount = $paymentRefund->amount_captured / 100;
                $totalRefundedAmount = $paymentRefund->amount_refunded / 100;
                $paymentIntentId = $paymentRefund->payment_intent;

                // Then define and call a method to handle the successful refund.

                $refund['capturedAmount'] = $capturedAmount;
                $refund['refundedAmount'] = $refundedAmount;
                $refund['totalRefundedAmount'] = $totalRefundedAmount;
                $refund['paymentIntentId'] = $paymentIntentId;

                $transactions = Curl::to($this->api_url . 'payment/update-refund')
                    ->withBearer($this->token)->withOption('USERAGENT', 'DonationPortal/1.0')
                    ->withData($refund)
                    ->asJson()
                    ->post();
                if ($transactions->success) {
                    Log::debug('******** Updated Refund in iCharms! ******************');
                } else {
                    Log::debug('******** Unable to update Refund in iCharms! Ref ID: ' . $paymentIntentId . ' ******************');
                }

                break;

            case 'payment_method.attached':

                // $paymentMethod = $event->data->object; // contains a \Stripe\PaymentMethod
                // Then define and call a method to handle the successful attachment of a PaymentMethod.
                // handlePaymentMethodAttached($paymentMethod);
                break;

            default:

                // Unexpected event type

                echo 'Received unknown event type';

        }

        http_response_code(200);
    }

    /**
     * Get All Data from this method.
     *
     */
    public function payStripe()
    {
        //include Stripe PHP library
        // require_once APPPATH . "third_party/stripe-php-7.67.0/init.php";

        \Stripe\Stripe::setApiKey(config('stripe.secret'));

        $message = null;
        $success = false;
        $charge = null;
        $data = array();

        header('Content-Type: application/json');

        try {

            // retrieve JSON from POST body
            $input = file_get_contents('php://input');
            $body = json_decode($input);

            $donor_id = $body->donor_id;
            $reference_id = $body->reference_id;
            //Creates timestamp that is needed to make up orderid
            $timestamp = strftime("%Y%m%d%H%M%S");
            //You can use any alphanumeric combination for the orderid. Although each transaction must have a unique orderid.
            $orderid = $donor_id . "-" . $timestamp . "-" . \Str::random(5);

            //charge a credit or a debit card
            $paymentIntent = \Stripe\PaymentIntent::create([
                "amount" => $body->amount * 100,
                "currency" => "gbp",
                "description" => "Payment through website",
                'metadata' => array(
                    'order_id' => $orderid,
                    'reference_id' => $reference_id,
                ),
                'receipt_email' => $body->email,
            ]);

        } catch (\Exception $e) {
            Log::debug($e);
            http_response_code(500);

            $message = $e->getMessage();

        }

        if ($paymentIntent) {
            //retrieve charge details
            $output = [
                'clientSecret' => $paymentIntent->client_secret,
            ];

            echo json_encode($output);

        }

    }

    public function checkSuccessStripe(Request $request)
    {
        Log::info("StripeController checkSuccessStripe()");
        Log::info(json_encode($request->all()));
        if ($request->id != -1) {

            $charge = json_decode($request->id, true);

            // Call Donation Api
            $data['txn_id'] = $charge['paymentIntent']['id'];
            $data['payment_amt'] = $charge['paymentIntent']['amount'] / 100;
            $data['currency_code'] = strtoupper($charge['paymentIntent']['currency']);
            $data['auth_code'] = '';
            $data['status'] = $charge['paymentIntent']['status'];
            $data['card_txn_no'] = $charge['paymentIntent']['id'];
            $data['payment_status'] = 'Completed';
            $data['reference_no'] = $request->reference_id;
            $data['payment_mode_code'] = 'STRIPE';

            if ($request->_auth) {
                $data['auth'] = 1;
                $data['donor_id'] = $request->donor_id;
            } else {
                $data['auth'] = 0;
                $data['session_id'] = session()->getId();
            }

            $response = Curl::to($this->api_url . 'payment/create-single-donation')
                ->withBearer($this->token)->withOption('USERAGENT', 'DonationPortal/1.0')
                ->withData($data)
                ->asJson()
                ->post();
            Log::info(json_encode($response));
            //   check if donation is created
            //   if not, refund amount and reverse cart

            if ($response->success) {

                $cart_data = Session::get('cart');
                // if (in_array('direct-debit', array_column($cart_data, 'donation_period'))) {

                //     return redirect()->route('payment.success', ['status' => 'both']);
                // }
                return redirect()->route('payment.success', ['status' => 'only-one-off']);
            } else {
                // reverse cart
                \Stripe\Stripe::setApiKey(config('stripe.secret'));

                Log::debug("Failed Trasn - Ref: " . $request->reference_id);

                /* \Stripe\Refund::create([
                'payment_intent' => $charge['paymentIntent']['id']
                ]); */

                // return redirect()->route('payment.fail');
                return redirect()->route('payment.success', ['status' => 'only-one-off']);
            }

        } else {

            /* reverse cart */
            /* TODO: */
            return redirect()->route('payment.fail');
        }
    }

    public function postPaymentStripe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'card_no' => 'required',
            'ccExpiryMonth' => 'required',
            'ccExpiryYear' => 'required',
            'cvvNumber' => 'required',
            //'amount' => 'required',
        ]);

        $input = $request->all();

        if ($validator->passes()) {

            $input = array_except($input, array('_token'));
            $stripe = Stripe\Stripe::setApiKey(config('stripe.secret'));
            try {
                $token = $stripe->tokens()->create([
                    'card' => [
                        'number' => $request->get('card_no'),
                        'exp_month' => $request->get('ccExpiryMonth'),
                        'exp_year' => $request->get('ccExpiryYear'),
                        'cvc' => $request->get('cvvNumber'),
                    ],
                ]);
                if (!isset($token['id'])) {
                    return redirect()->route('addmoney.paymentstripe');
                }
                $charge = $stripe->charges()->create([
                    'card' => $token['id'],
                    'currency' => 'USD',
                    'amount' => 20.49,
                    'description' => 'wallet',
                ]);

                if ($charge['status'] == 'succeeded') {
                    echo "<pre>";
                    print_r($charge);exit();
                    return redirect()->route('addmoney.paymentstripe');
                } else {
                    Session::put('error', 'Money not add in wallet!!');
                    return redirect()->route('addmoney.paymentstripe');
                }
            } catch (\Exception $e) {
                Session::put('error', $e->getMessage());
                return redirect()->route('addmoney.paymentstripe');
            } catch (\Cartalyst\Stripe\Exception\CardErrorException $e) {
                Session::put('error', $e->getMessage());
                return redirect()->route('addmoney.paywithstripe');
            } catch (\Cartalyst\Stripe\Exception\MissingParameterException $e) {
                Session::put('error', $e->getMessage());
                return redirect()->route('addmoney.paymentstripe');
            }
        }
    }

    public function init(Request $request)
    {
        Log::info("StripeController init");
        $currency = 'GBP';
        // log message
        Log::info('Stripe init');

        // Set API key
        \Stripe\Stripe::setApiKey(config('stripe.secret'));
        // \Stripe\Stripe::setApiVersion("2019-03-14");
        \Stripe\Stripe::setApiVersion("2020-08-27");

        $response = array(
            'status' => 0,
            'error' => array(
                'message' => 'Invalid Request!',
            ),
        );

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $input = file_get_contents('php://input');
            $request = json_decode($input);
        }

        if (json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(400);

            return response()->json($response);
            exit;
        }

        // second log message
        Log::info('working on Stripe init');

        if (!empty($request->createCheckoutSession)) {
            // third log message
            Log::info('working on Stripe init createCheckoutSession');

            $donor_session = $request->donor_session;
            $reference_id = $request->reference_id;

            Log::debug("donor_session -> $donor_session");
            Log::debug("reference_id -> $reference_id");

            // get cart details
            $cart_data = Session::get('cart');

            // Convert product price to cent
            // $stripeAmount = round($donor->donation_total_amount * 100, 2);

            // create stripe linbe items
            $items = array();
            foreach ($cart_data as $cart) {
                if ($cart->donation_period == 'one-off') {

                    $country_name = $cart->country_name == 'MNA' ? 'Most Needed Area' : $cart->country_name;
                    $temp = [
                        'price_data' => [
                            'product_data' => [
                                'name' => $cart->program_name,
                                'metadata' => [
                                    'pro_id' => $donor_session,
                                ],
                            ],
                            'unit_amount' => round($cart->donation_amount * 100, 2),
                            'currency' => $currency,
                        ],
                        'quantity' => $cart->quantity,
                        'description' => "For Country : " . $country_name,
                    ];

                    array_push($items, $temp);
                }
            }

            $api_error = null;
            // Create new Checkout Session for the order
            try {

                $checkout_session_array = [
                    'line_items' => [$items],
                    'mode' => 'payment',
                    'success_url' => route('stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url' => route('stripe.cancel'),
                    'metadata' => [
                        'donorOrSessionId' => $donor_session,
                        'guest' => Session::has('user') ? 'no' : 'yes',
                        'reference_id' => $reference_id,
                        "description" => "Payment through website",
                    ],
                ];

                if (Session::has('user')) {
                    $user = Session::get('user');
                    // Log::info($user);
                    // Log::info($user['email']);
                    $checkout_session_array['customer_email'] = $user['email'];
                }

                $checkout_session = \Stripe\Checkout\Session::create($checkout_session_array);
            } catch (\Exception $e) {
                $api_error = $e->getMessage();
            }
            //log api error
            Log::info('Stripe api error: ' . $api_error);
            // log message
            // Log::info('');
            if (empty($api_error) && $checkout_session) {
                $response = array(
                    'status' => 1,
                    'message' => 'Checkout Session created successfully!',
                    'sessionId' => $checkout_session->id,
                );
            } else {
                $response = array(
                    'status' => 0,
                    'error' => array(
                        'message' => 'Checkout Session creation failed! ' . $api_error,
                    ),
                );
            }
        }

        // log message
        Log::info('Stripe init end');

        // Return response
        return response()->json($response);

    }

    public function paymentSuccess(Request $request)
    {
        Log::info('Stripe paymentSuccess');

        return StripeService::success($request, $this->api_url, $this->token);
    }

    public function paymentCancelled(Request $request)
    {
        Log::info('Stripe paymentCancelled');
        Log::debug(json_encode($request));

        return redirect()->route('payment.cancel');
    }

}
