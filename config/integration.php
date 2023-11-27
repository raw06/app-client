<?php

return [
    'is' => [
        'id' => env('IC_ID', ''),
        'secret' => env('IC_SECRET', ''),
        'url' => [
            'integration' => env('IC_URL'),
            'authorize' => env('IC_URL_AUTHORIZE', ''),
            'token' => env('IC_URL_TOKEN')
        ],
        'redirect' => env('REDIRECT_APP')
    ]
];
