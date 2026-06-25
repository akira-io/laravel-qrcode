<?php

declare(strict_types=1);

use Akira\QrCode\QrCode;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;

it('caches generated raw qr codes', function (): void {
    $this->app->make(ConfigRepository::class)->set('cache.default', 'array');
    $cacheRepository = $this->app->make(CacheRepository::class);
    $cacheRepository->flush();

    $qrCode = resolve(QrCode::class)->format('svg')->cache(600, 'testing-qrcode');
    $cacheKey = $qrCode->cacheKeyFor('cached text');
    $generatedQrCode = $qrCode->generateRaw('cached text');

    expect($cacheRepository->get($cacheKey))->toBe($generatedQrCode);

    $cacheRepository->put($cacheKey, 'cached-hit', 600);

    expect($qrCode->generateRaw('cached text'))->toBe('cached-hit');
});

it('uses distinct cache keys for generation options', function (): void {
    $smallQrCodeKey = resolve(QrCode::class)
        ->format('svg')
        ->size(100)
        ->cache()
        ->cacheKeyFor('same text');

    $largeQrCodeKey = resolve(QrCode::class)
        ->format('svg')
        ->size(200)
        ->cache()
        ->cacheKeyFor('same text');

    expect($smallQrCodeKey)->not->toBe($largeQrCodeKey);
});

it('applies cache settings from config', function (): void {
    $this->app->make(ConfigRepository::class)->set('cache.default', 'array');
    $this->app->make(ConfigRepository::class)->set('qrcode.cache.enabled', true);
    $this->app->make(ConfigRepository::class)->set('qrcode.cache.ttl', 120);
    $this->app->make(ConfigRepository::class)->set('qrcode.cache.prefix', 'configured-qrcode');
    $cacheRepository = $this->app->make(CacheRepository::class);
    $cacheRepository->flush();

    $qrCode = resolve(QrCode::class)->format('svg');
    $cacheKey = $qrCode->cacheKeyFor('configured text');
    $generatedQrCode = $qrCode->generateRaw('configured text');

    expect($cacheKey)->toStartWith('configured-qrcode:');
    expect($cacheRepository->get($cacheKey))->toBe($generatedQrCode);
});

it('generates batches as collections keyed by input', function (): void {
    $generatedQrCodes = resolve(QrCode::class)
        ->format('svg')
        ->batch([
            'first' => 'First payload',
            'second' => 'Second payload',
        ]);

    expect($generatedQrCodes)->toBeInstanceOf(Collection::class);
    expect($generatedQrCodes->keys()->all())->toBe(['first', 'second']);
    expect($generatedQrCodes->get('first'))->toBeInstanceOf(HtmlString::class);
    expect($generatedQrCodes->get('second'))->toBeInstanceOf(HtmlString::class);
});

it('generates raw batches for storage workflows', function (): void {
    $generatedQrCodes = resolve(QrCode::class)
        ->format('svg')
        ->batchRaw([
            'first' => 'First payload',
            'second' => 'Second payload',
        ]);

    expect($generatedQrCodes->keys()->all())->toBe(['first', 'second']);
    expect($generatedQrCodes->get('first'))->toBeString()->toContain('<svg');
    expect($generatedQrCodes->get('second'))->toBeString()->toContain('<svg');
});
