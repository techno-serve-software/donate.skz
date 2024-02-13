<?php

return [

    // Show qurbani Button on page
    'qurbani_button' => env('QURBANI_BUTTON', false),

    'website_url' => env('WEBSITE_URL', ''),
    'api_url' => env('API_URL', ''),
    'api_token' => env('API_TOKEN', ''),

    'payment' => [
        'stripe' => env('PAYMENT_STRIPE', true),
        'netpay' => env('PAYMENT_NETPAY', false),
        'paypal' => env('PAYMENT_PAYPAL', false),
    ],

];
