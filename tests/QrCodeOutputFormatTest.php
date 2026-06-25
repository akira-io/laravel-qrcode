<?php

declare(strict_types=1);

use Akira\QrCode\QrCode;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;

it('uses imagick backend for webp output', function (): void {
    $qrCode = resolve(QrCode::class)->format('webp');

    expect($qrCode->getFormatter())->toBeInstanceOf(ImagickImageBackEnd::class);
});

it('generates raw webp output', function (): void {
    $qrCode = resolve(QrCode::class)->format('webp');
    $output = $qrCode->generateRaw('WebP payload');

    expect($output)->toBeString();
    expect(mb_substr((string) $output, 0, 4))->toBe('RIFF');
    expect(mb_substr((string) $output, 8, 4))->toBe('WEBP');
});

it('uses imagick backend for pdf output', function (): void {
    $qrCode = resolve(QrCode::class)->format('pdf');

    expect($qrCode->getFormatter())->toBeInstanceOf(ImagickImageBackEnd::class);
});

it('generates raw pdf output', function (): void {
    $qrCode = resolve(QrCode::class)->format('pdf');
    $output = $qrCode->generateRaw('PDF payload');

    expect($output)->toBeString();
    expect(mb_substr((string) $output, 0, 4))->toBe('%PDF');
});
