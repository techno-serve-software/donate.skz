<?php

// Netpay Settings & Credentials

return [

    /**
     * Set the application to sandbox or live
     * Default is set to sanbox
     */
    'mode' => env('NETPAY_MODE', 2), // 1: LIVE, 2: TEST

    'sandbox' => [
        'merchant' => env('NETPAY_DEV_MERCHANT_ID'),
        'username' => env('NETPAY_DEV_USERNAME'),
        'password' => env('NETPAY_DEV_PASSWORD'),
        'encrypt_iv' => env('NETPAY_DEV_ENCRYPTION_IV'),
        'encrypt_key' => env('NETPAY_DEV_ENCRYPTION_KEY', ''),
        'encrypt_method' => env('NETPAY_DEV_ENCRYPTION_METHOD', 'AES-128-CBC'),
    ],
    'live' => [
        'merchant' => env('NETPAY_LIVE_MERCHANT_ID'),
        'username' => env('NETPAY_LIVE_USERNAME'),
        'password' => env('NETPAY_LIVE_PASSWORD'),
        'encrypt_iv' => env('NETPAY_LIVE_ENCRYPTION_IV'),
        'encrypt_key' => env('NETPAY_LIVE_ENCRYPTION_KEY', ''),
        'encrypt_method' => env('NETPAY_LIVE_ENCRYPTION_METHOD', 'AES-128-CBC'),
    ],
];
