<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    
    'recaptcha' => [
        'site_key' => env('RECAPTCHA_SITE_KEY'),
        'secret_key' => env('RECAPTCHA_SECRET_KEY'),
    ],

    'razorpay' => [
        'key' => env('RAZORPAY_KEY', ''),
        'secret' => env('RAZORPAY_SECRET', ''),
    ],

    'shiprocket' => [
        'email'           => env('SHIPROCKET_EMAIL', ''),
        'password'        => env('SHIPROCKET_PASSWORD', ''),
        'base_url'        => 'https://apiv2.shiprocket.in',
        // Set SHIPROCKET_PICKUP_LOCATION in .env to the EXACT name of your pickup
        // address as registered under Settings > Manage Pickups in Shiprocket panel.
        'pickup_location' => env('SHIPROCKET_PICKUP_LOCATION', 'Primary'),
        // Set SHIPROCKET_CHANNEL_ID only if you use a custom sales channel (numeric ID).
        // Leave blank or omit if using the default WinkelKart channel.
        'channel_id'      => env('SHIPROCKET_CHANNEL_ID', ''),
    ],

];
