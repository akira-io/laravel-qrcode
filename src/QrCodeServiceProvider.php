<?php

declare(strict_types=1);

namespace Akira\QrCode;

use Illuminate\Support\ServiceProvider;
use Orchestra\Testbench\Foundation\Application;

final class QrCodeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind('qrcode', fn (Application $app) => $app->make(QrCode::class));
    }

    /**
     * @return class-string[]
     */
    public function provides(): array
    {
        return [QrCode::class];
    }
}
