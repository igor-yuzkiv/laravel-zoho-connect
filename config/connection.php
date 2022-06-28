<?php

return [
    'base_url' => 'https://accounts.zoho',
    'version'  => 'v2',

    'default_client'  => env('ZOHO_CONNECT_CLIENT_ID', ''),
    'default_storage' => env('ZOHO_CONNECT_STORAGE', 'eloquent'),

    'default_data_center' => "us",
    'data_center'         => [
        'us' => [
            'title'    => 'United States',
            'domain'   => '.com',
            'location' => 'us',
        ],
        'eu' => [
            'title'    => 'Europe',
            'domain'   => '.eu',
            'location' => 'eu',
        ],
        'in' => [
            'title'    => 'India',
            'domain'   => '.in',
            'location' => 'in',
        ],
        'au' => [
            'title'    => 'Australia',
            'domain'   => '.com.au',
            'location' => 'au',
        ],
        'jp' => [
            'title'    => 'Japan',
            'domain'   => '.jp',
            'location' => 'jp',
        ],
    ],

    'storage' => [
        'eloquent' => [
            'driver' => \ZohoConnect\Storage\EloquentStorage::class,
        ],
        'redis'    => [
            'driver'   => \ZohoConnect\Storage\RedisStorage::class,
            'prefix'   => 'zoho-auth',
            'database' => 'default'
        ],
    ],
];
