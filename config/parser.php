<?php
return [

    'use_proxy_list' => env('USE_PROXY_LIST', false),

    'log_to_mail' => env('APP_LOGTOEMAIL', false),

    'admin_emails' => env('ADMIN_EMAILS', 'pasha.klyuchnikov@gmail.com'),

    'donors' => [
        'eng' => [
            'BartecDe_Categories_Eng' => \App\Donors\BartecDe_Categories_Eng::class,
            'BartecDe_Products_Eng' => \App\Donors\BartecDe_Products_Eng::class,
        ]
    ],

    'version' => [
        'eng' => 1,
    ],
    'version_label' => [
        'eng' => 'Англоязычные',
    ]

];