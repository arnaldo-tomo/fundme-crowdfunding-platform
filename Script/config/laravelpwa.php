<?php

return [
    'name' => 'LaravelPWA',
    'manifest' => [
        'name' => env('PWA_SHORT_NAME'),
        'short_name' => env('PWA_SHORT_NAME'),
        'start_url' => '/',
        'background_color' => '#ffffff',
        'theme_color' => '#000000',
        'display' => 'standalone',
        'orientation'=> 'any',
        'status_bar'=> 'black',
        'icons' => [
            '72x72' => [
                'path' => env('PWA_ICON_72'),
                'purpose' => 'any'
            ],
            '96x96' => [
                'path' => env('PWA_ICON_96'),
                'purpose' => 'any'
            ],
            '128x128' => [
                'path' => env('PWA_ICON_128'),
                'purpose' => 'any'
            ],
            '144x144' => [
                'path' => env('PWA_ICON_144'),
                'purpose' => 'any'
            ],
            '152x152' => [
                'path' => env('PWA_ICON_152'),
                'purpose' => 'any'
            ],
            '384x384' => [
                'path' => env('PWA_ICON_384'),
                'purpose' => 'any'
            ],
            '512x512' => [
                'path' => env('PWA_ICON_512'),
                'purpose' => 'any'
            ],
        ],
        'splash' => [
            '640x1136' => env('PWA_SPLASH_640'),
            '750x1334' => env('PWA_SPLASH_750'),
            '1125x2436' => env('PWA_SPLASH_1125'),
            '1242x2208' => env('PWA_SPLASH_1242'),
            '1536x2048' => env('PWA_SPLASH_1536'),
            '1668x2224' => env('PWA_SPLASH_1668'),
            '2048x2732' => env('PWA_SPLASH_2048'),
        ],
        'shortcuts' => [],
        'custom' => []
    ]
];
