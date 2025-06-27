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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Claude AI Service Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Anthropic's Claude AI API used for generating
    | educational quiz questions based on the Juzoor model.
    |
    */

    'claude' => [
        'key' => env('CLAUDE_API_KEY'),
        'model' => env('CLAUDE_MODEL', 'claude-3-5-sonnet-20241022'),
        'max_tokens' => env('CLAUDE_MAX_TOKENS', 4000),
        'temperature' => env('CLAUDE_TEMPERATURE', 0.7),
        'cache_enabled' => env('CLAUDE_CACHE_ENABLED', true),
        'timeout' => env('CLAUDE_TIMEOUT', 120),
    ],
    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URL', '/auth/google/callback'),
    ],
    'lemonsqueezy' => [
        'api_key' => env('LEMONSQUEEZY_API_KEY'),
        'store_id' => env('LEMONSQUEEZY_STORE_ID'),
        'variant_id_pro' => env('LEMONSQUEEZY_VARIANT_ID_PRO'),
        'variant_id_premium' => env('LEMONSQUEEZY_VARIANT_ID_PREMIUM'),
        'signing_secret' => env('LEMONSQUEEZY_SIGNING_SECRET'),
    ],

];