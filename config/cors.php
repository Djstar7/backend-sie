<?php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie'], // toutes les routes API

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'http://localhost:5173',
        'http://localhost:5174',
        'http://localhost:5176',
        'http://127.0.0.1:5173',
        'http://127.0.0.1:5174',
        'https://supercurious-kathryn-fremdly.ngrok-free.dev',
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'], // autorise tous les headers, y compris Authorization

    'exposed_headers' => ['Authorization', 'Content-Type'], // si besoin d'exposer certains headers

    'max_age' => 0,

    'supports_credentials' => true, // requis pour les cookies/sessions
];
