<?php

declare(strict_types=1);

use Akira\QrCode\QrCode;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use Illuminate\Support\Facades\Config;

it('applies configured presets', function (): void {
    Config::set('qrcode.presets.ticket', [
        'format' => 'png',
        'size' => 512,
        'margin' => 8,
        'error_correction' => 'H',
    ]);

    $qrCode = resolve(QrCode::class)->preset('ticket');

    expect($qrCode->getFormatter())->toBeInstanceOf(ImagickImageBackEnd::class);
    expect($qrCode->getRendererStyle()->getSize())->toBe(512);
    expect($qrCode->getRendererStyle()->getMargin())->toBe(8);
});

it('allows fluent calls to override presets', function (): void {
    Config::set('qrcode.presets.card', [
        'size' => 256,
        'margin' => 2,
    ]);

    $qrCode = resolve(QrCode::class)
        ->preset('card')
        ->size(400)
        ->margin(10);

    expect($qrCode->getRendererStyle()->getSize())->toBe(400);
    expect($qrCode->getRendererStyle()->getMargin())->toBe(10);
});

it('applies configured themes', function (): void {
    Config::set('qrcode.themes.brand', [
        'color' => [10, 20, 30, 0],
        'background_color' => [240, 241, 242, 0],
    ]);

    $qrCode = resolve(QrCode::class)->theme('brand');

    expect($qrCode->getFill()->getForegroundColor()->toRgb()->getRed())->toBe(10);
    expect($qrCode->getFill()->getBackgroundColor()->toRgb()->getRed())->toBe(240);
});

it('throws when a preset is not configured', function (): void {
    resolve(QrCode::class)->preset('missing');
})->throws(InvalidArgumentException::class, 'QR code preset [missing] is not configured.');

it('throws when configured colors are invalid', function (): void {
    Config::set('qrcode.themes.invalid', [
        'color' => [10],
    ]);

    resolve(QrCode::class)->theme('invalid');
})->throws(InvalidArgumentException::class, 'Configured QR code colors must include red, green, and blue values.');
