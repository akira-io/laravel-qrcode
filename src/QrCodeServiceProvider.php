<?php

declare(strict_types=1);

namespace Akira\QrCode;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;

final class QrCodeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/qrcode.php', 'qrcode');

        $this->app->bind('qrcode', fn (Container $app): QrCode => $app->make(QrCode::class));
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/qrcode.php' => config_path('qrcode.php'),
        ], 'qrcode-config');
    }

    /**
     * @return array<int, class-string|string>
     */
    public function provides(): array
    {
        return [QrCode::class, 'qrcode'];
    }
}
