<?php

declare(strict_types=1);

use Akira\QrCode\Facades\QrCode as QrCodeFacade;
use Akira\QrCode\QrCode;
use Illuminate\Support\HtmlString;

if (! function_exists('qrcode')) {
    /**
     * @return QrCode|HtmlString|string|null
     */
    function qrcode(?string $text = null): mixed
    {
        if ($text === null) {
            return resolve(QrCode::class);
        }

        return QrCodeFacade::generate($text);
    }
}
