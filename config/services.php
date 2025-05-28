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
        'model' => env('CLAUDE_MODEL', 'claude-3-sonnet-20240229'),
        'max_tokens' => (int) env('CLAUDE_MAX_TOKENS', 4000),
        'temperature' => (float) env('CLAUDE_TEMPERATURE', 0.7),
        'timeout' => (int) env('CLAUDE_TIMEOUT', 60),
        'cache_enabled' => (bool) env('CLAUDE_CACHE_ENABLED', false),
    ],

];