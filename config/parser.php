<?php
return [

    'use_proxy_list' => env('USE_PROXY_LIST', false),

    'log_to_mail' => env('APP_LOGTOEMAIL', false),

    'admin_emails' => env('ADMIN_EMAILS', 'pasha.klyuchnikov@gmail.com'),

    'donors' => [
        'eng' => [
            'Template_Msk' => \App\Donors\Template_Msk::class,
        ]
    ],

    'version' => [
        'eng' => 1,
    ],
    'version_label' => [
        'eng' => 'Англоязычные',
    ]

];