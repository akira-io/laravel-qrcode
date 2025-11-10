<?php

use Akira\QrCode\Facades\QrCode as QrCodeFacade;
use Akira\QrCode\QrCode;

if (! function_exists('qrcode')) {
    function qrcode(?string $text = null): QrCode|string
    {
        if ($text === null) {
            return app(QrCode::class);
        }

        return QrCodeFacade::generate($text);
    }
}
