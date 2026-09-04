<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Resend, Postmark, AWS, and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'orange_money' => [
        'client_id' => env('ORANGE_MONEY_CLIENT_ID'),
        'client_secret' => env('ORANGE_MONEY_CLIENT_SECRET'),
        'merchant_key' => env('ORANGE_MONEY_MERCHANT_KEY'),
        'currency' => env('ORANGE_MONEY_CURRENCY', 'OUV'),
        'oauth_token_url' => env('ORANGE_MONEY_OAUTH_TOKEN_URL', 'https://api.orange.com/oauth/v3/token'),
        'webpayment_url' => env('ORANGE_MONEY_WEBPAYMENT_URL', 'https://api.orange.com/orange-money-webpay/dev/v1/webpayment'),
        'transaction_status_url' => env('ORANGE_MONEY_TRANSACTION_STATUS_URL', 'https://api.orange.com/orange-money-webpay/dev/v1/transactionstatus'),
        'ssl_verify' => env('ORANGE_MONEY_SSL_VERIFY', true),
        'ca_cert_path' => env('ORANGE_MONEY_CA_CERT_PATH', ''),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI', env('APP_URL', 'http://localhost:8000') . '/auth/google/callback'),
    ],

];
