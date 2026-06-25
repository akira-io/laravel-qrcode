<?php

declare(strict_types=1);

return [
    'default_data' => env('QR_CODE_DEFAULT_DATA', ''),

    'format' => env('QR_CODE_FORMAT', 'png'),

    'size' => (int) (env('QR_CODE_SIZE', 200)),

    'margin' => (int) (env('QR_CODE_MARGIN', 4)),

    'color' => [
        (int) (env('QR_CODE_COLOR_R', 0)),
        (int) (env('QR_CODE_COLOR_G', 0)),
        (int) (env('QR_CODE_COLOR_B', 0)),
        (int) (env('QR_CODE_COLOR_A', 0)),
    ],

    'background_color' => [
        (int) (env('QR_CODE_BACKGROUND_COLOR_R', 255)),
        (int) (env('QR_CODE_BACKGROUND_COLOR_G', 255)),
        (int) (env('QR_CODE_BACKGROUND_COLOR_B', 255)),
        (int) (env('QR_CODE_BACKGROUND_COLOR_A', 0)),
    ],

    'error_correction' => env('QR_CODE_ERROR_CORRECTION', 'H'),

    'encoding' => env('QR_CODE_ENCODING', 'UTF-8'),

    'merge' => [
        'percentage' => env('QR_CODE_MERGE_PERCENTAGE', 0.2),
        'absolute' => env('QR_CODE_MERGE_ABSOLUTE', false),
    ],

    'cache' => [
        'enabled' => filter_var(env('QR_CODE_CACHE_ENABLED', false), FILTER_VALIDATE_BOOL),
        'ttl' => (int) env('QR_CODE_CACHE_TTL', 3600),
        'prefix' => env('QR_CODE_CACHE_PREFIX', 'qrcode'),
    ],
];
