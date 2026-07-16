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

it('generates an opaque svg foreground by default', function (): void {
    $svg = (string) resolve(QrCode::class)->format('svg')->generate('opacity check');

    expect($svg)
        ->toContain('fill="#000000"')
        ->not->toContain('fill-opacity="0"');
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
