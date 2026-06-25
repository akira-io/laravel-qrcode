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

    'presets' => [
        'print' => [
            'format' => 'png',
            'size' => 600,
            'margin' => 4,
            'error_correction' => 'H',
        ],
    ],

    'themes' => [
        'light' => [
            'color' => [0, 0, 0, 0],
            'background_color' => [255, 255, 255, 0],
        ],
        'dark' => [
            'color' => [255, 255, 255, 0],
            'background_color' => [17, 24, 39, 0],
        ],
    ],
];
