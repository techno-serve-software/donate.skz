<?php

namespace App\Services;

// use App\Models\Donation;
// use App\Models\OnlineTransaction;
use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;
use Session;

class Stripe
{
    public static function success(Request $request, $api_url, $token)
    {
        $payment_id = $statusMsg = '';
        $status = 'error';
        $donation_id = '-1';

        // Check whether stripe checkout session is not empty
        if (!empty($request->session_id)) {
            $session_id = $request->session_id;

            // FIXME: get cart transaction details
            $result = '0';

            if ($result) {

                // Transaction details

                $payment_id = $result->id;
                $transactionID = $result->transaction_id;
                $paidAmount = $result->response_amount;
                $paidCurrency = $result->currency;
                $payment_status = $result->payment_status;

                // $customer_name = $result->customer_name;
                // $customer_email = $result->customer_email;

                return redirect()->route('payment.success', ['status' => 'only-one-off']);

            } else {

                // Set API key
                \Stripe\Stripe::setApiKey(config('stripe.secret'));

                // Fetch the Checkout Session to display the JSON result on the success page
                try {
                    $checkout_session = \Stripe\Checkout\Session::retrieve($session_id);
                } catch (\Exception $e) {
                    $api_error = $e->getMessage();
                }

                \Log::info("Checkout Session");
                // \Log::info(json_encode($ck_session));

                $guest = $checkout_session->metadata->guest;
                \Log::info($checkout_session->metadata->guest);
                $description = $checkout_session->metadata->description;
                \Log::info($checkout_session->metadata->description);
                $donorOrSessionId = $checkout_session->metadata->donorOrSessionId;
                \Log::info($checkout_session->metadata->donorOrSessionId);
                $reference_id = $checkout_session->metadata->reference_id;
                \Log::info($checkout_session->metadata->reference_id);
                // $donation_id = $checkout_session->metadata->donation_id;
                // $donor_id = $checkout_session->metadata->donor_id;

                if (empty($api_error) && $checkout_session) {
                    // Retrieve the details of a PaymentIntent
                    try {
                        $paymentIntent = \Stripe\PaymentIntent::retrieve($checkout_session->payment_intent);
                    } catch (\Stripe\Exception\ApiErrorException $e) {
                        $api_error = $e->getMessage();
                    }

                    // Retrieves the details of customer
                    try {
                        $customer = \Stripe\Customer::retrieve($checkout_session->customer);
                    } catch (\Stripe\Exception\ApiErrorException $e) {
                        $api_error = $e->getMessage();
                    }

                    if (empty($api_error) && $paymentIntent) {

                        \Log::debug(json_encode($checkout_session));
                        // Check whether the payment was successful
                        if (!empty($paymentIntent) && $paymentIntent->status == 'succeeded') {
                            // Transaction details
                            $transactionID = $paymentIntent->id;

                            $paidAmount = $paymentIntent->amount;
                            $paidAmount = ($paidAmount / 100);
                            $paidCurrency = $paymentIntent->currency;
                            $payment_status = $paymentIntent->status;

                            // Customer details
                            $customer_name = $customer_email = '';
                            if (!empty($customer)) {
                                $customer_name = !empty($customer->name) ? $customer->name : '';
                                $customer_email = !empty($customer->email) ? $customer->email : '';
                            }

                            // FIXME:
                            // Check if any transaction data is exists already with the same TXN ID
                            // $result = OnlineTransaction::query()->whereTransactionId($transactionID)->first();
                            $result = false;

                            // $item_number = $checkout_session->metadata->donation_id;
                            // $item_price = $checkout_session->amount_total;

                            if (!empty($result)) {
                                $payment_id = $result->id;
                            } else {

                                $data['txn_id'] = $transactionID;
                                $data['payment_amt'] = $paidAmount;
                                $data['currency_code'] = strtoupper($paidCurrency);
                                $data['auth_code'] = '';
                                $data['status'] = $payment_status;
                                $data['card_txn_no'] = $transactionID;
                                $data['payment_status'] = 'Completed';
                                $data['reference_no'] = $reference_id;
                                $data['payment_mode_code'] = 'STRIPE';

                                if ($guest == 'no') {
                                    $data['auth'] = 1;
                                    // $data['donor_id'] = $request->session()->get('user')['donor_id'];
                                    $data['donor_id'] = $donorOrSessionId;
                                } else {
                                    $data['auth'] = 0;
                                    // $data['session_id'] = session()->getId();
                                    $data['session_id'] = $donorOrSessionId;
                                }

                                $response = Curl::to($api_url . 'payment/create-single-donation')
                                    ->withBearer($token)
                                    ->withData($data)
                                    ->asJson()
                                    ->post();
                                \Log::info(json_encode($response));
                                //   check if donation is created
                                //   if not, refund amount and reverse cart

                                if ($response->success) {

                                    $cart_data = Session::get('cart');
                                    // if (in_array('direct-debit', array_column($cart_data, 'donation_period'))) {

                                    //     return redirect()->route('payment.success', ['status' => 'both']);
                                    // }
                                    return redirect()->route('payment.success', ['status' => 'only-one-off','payment' => $paidAmount, 'ref' => $transactionID, 'donor_name' => $response->donor_name]);
                                } else {
                                    // reverse cart
                                    \Stripe\Stripe::setApiKey(config('stripe.secret'));

                                    \Log::debug("Failed Trans - Ref: " . $request->reference_id);

                                    return redirect()->route('payment.success', ['status' => 'only-one-off']);
                                }
                            }

                            $status = 'success';
                            $statusMsg = 'Your Payment has been Successful!';
                        } else {
                            $statusMsg = "Transaction has been failed!";
                        }
                    } else {
                        $statusMsg = "Unable to fetch the transaction details! $api_error";
                    }
                } else {
                    $statusMsg = "Invalid Transaction! $api_error";
                }

                if ($status == 'error') {

                    return redirect()->route('payment.fail');

                    // Donation::query()
                    //     ->where('id', $donation_id)
                    //     ->update([
                    //         'failure_reason' => $statusMsg,
                    //         'status' => 'Failed',
                    //         'updated_by' => auth()->user()->id,
                    //     ]);

                }
            }
        } else {
            $statusMsg = "Invalid Request!";
        }

        $server = config('app.url');
        return redirect()->to($server . "/donation/" . $donation_id . "/edit")->send();

    }

    public static function cancel(Request $request)
    {
        \Log::info("Stripe Service cancel()");
        \Log::debug(json_encode($request));
        \Log::debug(json_encode($request->donation_id));

        // $server = config('app.url');

        // $donation_id = $request->donation_id;

        // $donation = Donation::find($donation_id);
        // $donation->failure_reason = 'Cancelled by user';
        // $donation->status = 'Failed';
        // $donation->updated_by = auth()->user()->id;

        // $donation->save();

        // return redirect()->to($server . "/donation/" . $donation_id . "/edit")->send();
        // return redirect()->route('donation.edit', $donation_id);

        return redirect()->route('payment.fail');

    }

}
