<?php

declare(strict_types=1);

use Akira\QrCode\QrCode;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\HtmlString;

beforeEach(function (): void {
    config()->set('cache.default', 'array');
    Cache::flush();
});

it('caches generated raw qr codes', function (): void {
    $qrCode = resolve(QrCode::class)->format('svg')->cache(600, 'testing-qrcode');
    $cacheKey = $qrCode->cacheKeyFor('cached text');
    $generatedQrCode = $qrCode->generateRaw('cached text');

    expect(Cache::get($cacheKey))->toBe($generatedQrCode);

    Cache::put($cacheKey, 'cached-hit', 600);

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
    config()->set('qrcode.cache.enabled', true);
    config()->set('qrcode.cache.ttl', 120);
    config()->set('qrcode.cache.prefix', 'configured-qrcode');

    $qrCode = resolve(QrCode::class)->format('svg');
    $cacheKey = $qrCode->cacheKeyFor('configured text');
    $generatedQrCode = $qrCode->generateRaw('configured text');

    expect($cacheKey)->toStartWith('configured-qrcode:');
    expect(Cache::get($cacheKey))->toBe($generatedQrCode);
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
