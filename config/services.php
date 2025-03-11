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

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'tbc' => [
        'endpoint' => env('TBC_ENDPOINT', 'https://checkout.tbcbank.ge/api/checkout/v1/transactions'),
        'api_key'  => env('TBC_API_KEY'),
        'app_id'   => env('TBC_APP_ID'),
        'sandbox'  => env('TBC_SANDBOX', true), // Enable sandbox mode
    ],

    'bog' => [
        'endpoint' => env('BOG_ENDPOINT', 'https://api.bog.com/payment'), // Update this URL per official docs
        'api_key'  => env('BOG_API_KEY', 'your_bog_api_key_here'),
    ],

];
