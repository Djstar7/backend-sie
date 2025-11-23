<?php

return [

    'paths' => ['api/*'], // toutes les routes API

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        env('FRONTEND_URL', 'http://localhost:5173',),
        'http://localhost:5174',
        'http://localhost:5176'
        // 'https://supercurious-kathryn-fremdly.ngrok-free.dev',
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'], // autorise tous les headers, y compris Authorization

    'exposed_headers' => ['Authorization', 'Content-Type'], // si besoin d’exposer certains headers

    'max_age' => 0,

    'supports_credentials' => false, // tu n’utilises pas de cookies
];
