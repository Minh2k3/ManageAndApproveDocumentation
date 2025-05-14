<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*', 'api/*/*', 'api/documents/*', 'sanctum/csrf-cookie', 'login', 'logout', 'password/reset', 'password/email', 'password/confirm', 'user/verify-email/*', 'user/*'],

    'allowed_methods' => ['PUT', 'POST', 'GET', 'DELETE', 'OPTIONS'],   

    'allowed_origins' => ['localhost:5173', 'http://localhost:5173'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];
