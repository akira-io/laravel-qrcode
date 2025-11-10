<?php

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
    | Supported: "png", "eps", "svg"
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
    'size' => (int) (env('QR_CODE_SIZE') ?? 200),

    /*
    |--------------------------------------------------------------------------
    | Default Margin
    |--------------------------------------------------------------------------
    |
    | This option controls the default margin around the QR code.
    |
    */
    'margin' => (int) (env('QR_CODE_MARGIN') ?? 4),

    /*
    |--------------------------------------------------------------------------
    | Default Color
    |--------------------------------------------------------------------------
    |
    | This option controls the default foreground color of the QR code.
    | Format: [R, G, B, A] where each value is 0-255
    |
    */
    'color' => [
        (int) (env('QR_CODE_COLOR_R') ?? 0),
        (int) (env('QR_CODE_COLOR_G') ?? 0),
        (int) (env('QR_CODE_COLOR_B') ?? 0),
        (int) (env('QR_CODE_COLOR_A') ?? 0),
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Background Color
    |--------------------------------------------------------------------------
    |
    | This option controls the default background color of the QR code.
    | Format: [R, G, B, A] where each value is 0-255 and A is optional between 0-127
    |
    */
    'background_color' => [
        (int) (env('QR_CODE_BACKGROUND_COLOR_R') ?? 255),
        (int) (env('QR_CODE_BACKGROUND_COLOR_G') ?? 255),
        (int) (env('QR_CODE_BACKGROUND_COLOR_B') ?? 255),
        (int) (env('QR_CODE_BACKGROUND_COLOR_A') ?? 0),
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
];
