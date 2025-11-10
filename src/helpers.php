<?php

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
            return app(QrCode::class);
        }

        return QrCodeFacade::generate($text);
    }
}
