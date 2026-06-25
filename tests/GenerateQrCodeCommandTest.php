<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;

it('generates a single qr code file', function (): void {
    $output = __DIR__.'/images/generated-command.svg';

    $this->artisan('qrcode:generate', [
        'text' => 'Command payload',
        '--output' => $output,
        '--format' => 'svg',
        '--size' => '220',
        '--error-correction' => 'H',
    ])->assertSuccessful();

    expect(File::exists($output))->toBeTrue();
    expect(File::get($output))->toContain('<svg');

    File::delete($output);
});

it('generates qr code files from csv input', function (): void {
    $batchPath = __DIR__.'/images/generated-command-batch.csv';
    $firstOutput = __DIR__.'/images/generated-command-first.svg';
    $secondOutput = __DIR__.'/images/generated-command-second.svg';

    File::put($batchPath, "First payload,{$firstOutput}\nSecond payload,{$secondOutput}\n");

    $this->artisan('qrcode:generate', [
        '--batch' => $batchPath,
        '--format' => 'svg',
    ])->assertSuccessful();

    expect(File::exists($firstOutput))->toBeTrue();
    expect(File::exists($secondOutput))->toBeTrue();

    File::delete([$batchPath, $firstOutput, $secondOutput]);
});

it('returns a validation error for unwritable output paths', function (): void {
    $this->artisan('qrcode:generate', [
        'text' => 'Command payload',
        '--output' => __DIR__.'/missing-directory/qrcode.svg',
        '--format' => 'svg',
    ])
        ->expectsOutputToContain('Output directory is not writable')
        ->assertFailed();
});
