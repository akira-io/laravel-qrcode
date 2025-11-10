<?php

namespace Akira\QrCode;

use Illuminate\Support\ServiceProvider;

class QrCodeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind('qrcode', function ($app) {
            return $app->make(QrCode::class);
        });
    }

    /**
     * @return class-string[]
     */
    public function provides(): array
    {
        return [QrCode::class];
    }
}
