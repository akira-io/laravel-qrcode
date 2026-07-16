<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Default Data
    |--------------------------------------------------------------------------
    |
    | This is the default data that will be used when generating a QR code
    | without explicitly providing the data.
    |
    */
    'default_data' => env('QR_CODE_DEFAULT_DATA', ''),

    /*
    |--------------------------------------------------------------------------
    | Default Format
    |--------------------------------------------------------------------------
    |
    | This option controls the default format that will be used when
    | generating QR codes.
    |
    | Supported: "png", "eps", "svg", "webp", "pdf"
    |
    */
    'format' => env('QR_CODE_FORMAT', 'png'),

    /*
    |--------------------------------------------------------------------------
    | Default Size
    |--------------------------------------------------------------------------
    |
    | This option controls the default size of the QR code in pixels.
    |
    */
    'size' => (int) (env('QR_CODE_SIZE', 200)),

    /*
    |--------------------------------------------------------------------------
    | Default Margin
    |--------------------------------------------------------------------------
    |
    | This option controls the default margin around the QR code.
    |
    */
    'margin' => (int) (env('QR_CODE_MARGIN', 4)),

    /*
    |--------------------------------------------------------------------------
    | Default Color
    |--------------------------------------------------------------------------
    |
    | This option controls the default foreground color of the QR code.
    | Format: [R, G, B, A] where R, G, B are 0-255 and A (opacity) is 0-100.
    | Leave A unset for a fully opaque color.
    |
    */
    'color' => [
        (int) (env('QR_CODE_COLOR_R', 0)),
        (int) (env('QR_CODE_COLOR_G', 0)),
        (int) (env('QR_CODE_COLOR_B', 0)),
        is_numeric(env('QR_CODE_COLOR_A')) ? (int) env('QR_CODE_COLOR_A') : null,
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Background Color
    |--------------------------------------------------------------------------
    |
    | This option controls the default background color of the QR code.
    | Format: [R, G, B, A] where R, G, B are 0-255 and A (opacity) is 0-100.
    | Leave A unset for a fully opaque color.
    |
    */
    'background_color' => [
        (int) (env('QR_CODE_BACKGROUND_COLOR_R', 255)),
        (int) (env('QR_CODE_BACKGROUND_COLOR_G', 255)),
        (int) (env('QR_CODE_BACKGROUND_COLOR_B', 255)),
        is_numeric(env('QR_CODE_BACKGROUND_COLOR_A')) ? (int) env('QR_CODE_BACKGROUND_COLOR_A') : null,
    ],

    /*
    |--------------------------------------------------------------------------
    | Error Correction Level
    |--------------------------------------------------------------------------
    |
    | This option controls the error correction level of the QR code.
    |
    | Supported: 'L', 'M', 'Q', 'H'
    |
    */
    'error_correction' => env('QR_CODE_ERROR_CORRECTION', 'H'),

    /*
    |--------------------------------------------------------------------------
    | Encoding
    |--------------------------------------------------------------------------
    |
    | This option controls the character encoding of the QR code.
    |
    */
    'encoding' => env('QR_CODE_ENCODING', 'UTF-8'),

    /*
    |--------------------------------------------------------------------------
    | Merge Options
    |--------------------------------------------------------------------------
    |
    | These options control the default behavior when merging an image
    | with the QR code.
    |
    */
    'merge' => [
        'percentage' => env('QR_CODE_MERGE_PERCENTAGE', 0.2),
        'absolute' => env('QR_CODE_MERGE_ABSOLUTE', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Options
    |--------------------------------------------------------------------------
    |
    | These options control cache-backed QR code generation.
    |
    */
    'cache' => [
        'enabled' => filter_var(env('QR_CODE_CACHE_ENABLED', false), FILTER_VALIDATE_BOOL),
        'ttl' => (int) env('QR_CODE_CACHE_TTL', 3600),
        'prefix' => env('QR_CODE_CACHE_PREFIX', 'qrcode'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Presets
    |--------------------------------------------------------------------------
    |
    | These presets provide reusable QR code option groups that can be applied
    | before fluent per-code overrides.
    |
    */
    'presets' => [
        'print' => [
            'format' => 'png',
            'size' => 600,
            'margin' => 4,
            'error_correction' => 'H',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Themes
    |--------------------------------------------------------------------------
    |
    | These themes provide reusable foreground and background color sets.
    |
    */
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
