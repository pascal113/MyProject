<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
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

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\Models\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook' => [
            'secret' => env('STRIPE_WEBHOOK_SECRET'),
            'tolerance' => env('STRIPE_WEBHOOK_TOLERANCE', 300),
        ],
    ],

    'googlemaps' => [
        'key' => env('GOOGLE_MAPS_KEY', ''),
        'center' => [
            'lat' => env('GOOGLE_MAPS_DEFAULT_CENTER_LAT', '47.342414'),
            'lng' => env('GOOGLE_MAPS_DEFAULT_CENTER_LNG', '-122.221868'),
        ],
        'zoom' => env('GOOGLE_MAPS_DEFAULT_ZOOM', 11),
    ],

    'payeezy' => [
        'payment_page_url' => env('PAYEEZY_PAYMENT_PAGE_URL', ''),
        'payment_page_id' => env('PAYEEZY_PAYMENT_PAGE_ID', ''),
        'payment_page_key' => env('PAYEEZY_PAYMENT_PAGE_KEY', ''),
        'payment_page_response_key' => env('PAYEEZY_PAYMENT_PAGE_REPONSE_KEY', ''),
        'api_base_url' => env('PAYEEZY_API_BASE_URL', 'https://api.globalgatewaye4.firstdata.com'),
        'api_endpoint' => env('PAYEEZY_API_ENDPOINT', '/transaction/v31'),
        'api_gateway_id' => env('PAYEEZY_API_GATEWAY_ID'),
        'api_key_id' => env('PAYEEZY_API_KEY_ID'),
        'api_hmac_key' => env('PAYEEZY_API_HMAC_KEY'),
        'api_password' => env('PAYEEZY_API_PASSWORD'),
        'pem_filename' => stristr(env('PAYEEZY_API_BASE_URL'), 'demo') ? base_path('demo.globalgatewaye4.firstdata.com.pem') : base_path('globalgatewaye4.firstdata.com.pem'),
    ],

    'facebook_pixel' => [
        'id' => env('FACEBOOK_PIXEL_ID', ''),
    ],

    'jango' => [
        'api_key' => env('JANGO_API_KEY', ''),
        'api_url' => env('JANGO_API_URL', ''),
        'mailing_list_name' => env('JANGO_MAILING_LIST_NAME', ''),
    ],

    'gateway' => [
        'base_url' => env('GATEWAY_BASE_URL'),
        'app_key' => env('GATEWAY_APP_KEY'),
        'app_secret' => env('GATEWAY_APP_SECRET'),
        'oauth_register_url' => env('GATEWAY_OAUTH_REGISTER_URL', '/register'),
        'oauth_login_url' => env('GATEWAY_OAUTH_LOGIN_URL', '/login'),
        'oauth_logout_url' => env('GATEWAY_OAUTH_LOGOUT_URL', '/sso/logout'),
        'forgot_password_url' => env('GATEWAY_FORGOT_PASSWORD_URL', '/forgot-password'),
        'reactivate_url' => env('GATEWAY_REACTIVATE_URL', '/reactivate'),
    ],

    'laravelpassport' => [
        'host' => env('GATEWAY_BASE_URL'),
        'redirect' => env('GATEWAY_OAUTH_CLIENT_REDIRECT'),
        'client_id' => env('GATEWAY_OAUTH_CLIENT_KEY'),
        'client_secret' => env('GATEWAY_OAUTH_CLIENT_SECRET'),
    ],

];
