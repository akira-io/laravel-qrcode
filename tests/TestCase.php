<?php

declare(strict_types=1);

namespace Tests;

use Akira\QrCode\QrCodeServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            QrCodeServiceProvider::class,
        ];
    }
}
