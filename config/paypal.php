<?php
/**
 * PayPal Setting & API Credentials
 */

return [
    'mode' => env('PAYPAL_MODE', 'sandbox'), // Can only be 'sandbox' Or 'live'. If empty or invalid, 'live' will be used.
    'sandbox' => [
        'username' => env('PAYPAL_SANDBOX_API_USERNAME'),
        'password' => env('PAYPAL_SANDBOX_API_PASSWORD'),
        'secret' => env('PAYPAL_SANDBOX_API_SECRET'),
        'certificate' => env('PAYPAL_SANDBOX_API_CERTIFICATE', ''),
        'app_id' => env('PAYPAL_SANDBOX_APP_ID', ''), // Used for testing Adaptive Payments API in sandbox mode
    ],
    'live' => [
        'username' => env('PAYPAL_LIVE_API_USERNAME', ''),
        'password' => env('PAYPAL_LIVE_API_PASSWORD', ''),
        'secret' => env('PAYPAL_LIVE_API_SECRET', ''),
        'certificate' => env('PAYPAL_LIVE_API_CERTIFICATE', ''),
        'app_id' => env('PAYPAL_LIVE_APP_ID', ''), // Used for Adaptive Payments API
    ],

    'payment_action' => env('PAYPAL_PAYMENT_ACTION', 'Sale'), // Can only be 'Sale', 'Authorization' or 'Order'
    'currency' => env('PAYPAL_CURRENCY', 'GBP'),
    'billing_type' => env('PAYPAL_BILLING_TYPE', 'MerchantInitiatedBilling'),
    'notify_url' => env('PAYPAL_NOTIFY_URL', ''), // Change this accordingly for your application.
    'locale' => env('PAYPAL_LOCALE', ''), // force gateway language  i.e. it_IT, es_ES, en_US ... (for express checkout only)
    'validate_ssl' => env('PAYPAL_VALIDATE_SSL', true), // Validate SSL when creating api client.
    'invoice_prefix' => env('PAYPAL_INVOICE_PREFIX', 'PAYPALDEMOAPP'),
];
