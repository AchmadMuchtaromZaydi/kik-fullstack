<?php

return [
    'default' => env('MAIL_MAILER', 'smtp'),

    'mailers' => [
        'smtp' => [
            'transport' => 'smtp',
            'host' => env('MAIL_HOST', 'kesenianbanyuwangi.com'),
            'port' => env('MAIL_PORT', 465),
            'encryption' => env('MAIL_ENCRYPTION', 'ssl'),
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'timeout' => null,
            'auth_mode' => null,
        ],
        // ... lainnya
    ],

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'info@kesenianbanyuwangi.com'),
        'name' => env('MAIL_FROM_NAME', 'Kartu Induk Kesenian Banyuwangi'),
    ],
];
