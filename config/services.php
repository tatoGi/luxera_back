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
    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => function() {
            $baseUrl = rtrim(env('APP_URL', 'http://localhost:8000'), '/');
            $locale = app()->getLocale();
            // Ensure the URL has a protocol but not duplicated
            if (strpos($baseUrl, 'http') !== 0) {
                $baseUrl = 'https://' . ltrim($baseUrl, '/');
            }
            // Remove any existing locale from the URL to prevent duplication
            $baseUrl = preg_replace('#/\w{2}(?=/|$)#', '', $baseUrl);
            return $baseUrl . '/' . $locale . '/auth/google/callback';
        },
    ],

];
