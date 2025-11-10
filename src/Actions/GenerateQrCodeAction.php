<?php

namespace Akira\QrCode\Actions;

use Akira\QrCode\Image;
use Akira\QrCode\ImageMerge;
use BaconQrCode\Writer;
use Illuminate\Support\HtmlString;

class GenerateQrCodeAction
{
    public function handle(
        string $text,
        Writer $writer,
        string $encoding,
        ?\BaconQrCode\Common\ErrorCorrectionLevel $errorCorrection,
        ?string $imageMerge = null,
        float $imagePercentage = 0.2,
        string $format = 'svg',
        ?string $filename = null
    ): string|HtmlString|null {
        $qrCode = $writer->writeString($text, $encoding, $errorCorrection);

        if ($imageMerge !== null && $format === 'png') {
            $merger = new ImageMerge(new Image($qrCode), new Image($imageMerge));
            $qrCode = $merger->merge($imagePercentage);
        }

        if ($filename) {
            file_put_contents($filename, $qrCode);
            return null;
        }

        // Convert PNG to base64 data URL for HTML display
        if ($format === 'png' && class_exists(HtmlString::class)) {
            $base64 = base64_encode($qrCode);
            return new HtmlString('<img src="data:image/png;base64,' . $base64 . '" alt="QR Code">');
        }

        if (class_exists(HtmlString::class)) {
            return new HtmlString($qrCode);
        }

        return $qrCode;
    }
}
