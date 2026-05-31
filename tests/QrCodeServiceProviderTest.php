<?php

declare(strict_types=1);

use Akira\QrCode\QrCode;
use Akira\QrCode\QrCodeServiceProvider;
use BaconQrCode\Renderer\Image\EpsImageBackEnd;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Foundation\Application;

it('resolves qrcode in a real Laravel application container', function (): void {
    $app = new Application(__DIR__);
    $app->instance('config', new Repository());
    $app->alias('config', ConfigRepository::class);

    $provider = new QrCodeServiceProvider($app);
    $provider->register();

    expect($app->make('qrcode'))->toBeInstanceOf(QrCode::class);
});

it('applies qrcode configuration defaults to new generator instances', function (): void {
    config()->set('qrcode.format', 'eps');
    config()->set('qrcode.size', 321);
    config()->set('qrcode.margin', 7);
    config()->set('qrcode.color', [10, 20, 30, 0]);
    config()->set('qrcode.background_color', [240, 241, 242, 0]);

    $qrCode = resolve(QrCode::class);

    expect($qrCode->getFormatter())->toBeInstanceOf(EpsImageBackEnd::class);
    expect($qrCode->getRendererStyle()->getSize())->toBe(321);
    expect($qrCode->getRendererStyle()->getMargin())->toBe(7);
    expect($qrCode->getFill()->getForegroundColor()->toRgb()->getRed())->toBe(10);
    expect($qrCode->getFill()->getBackgroundColor()->toRgb()->getRed())->toBe(240);
});
