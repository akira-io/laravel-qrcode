<?php

declare(strict_types=1);

namespace Akira\QrCode\Facades;

use Illuminate\Support\Facades\Facade;
use Akira\QrCode\QrCode as Generator;

/**
 * @method static \Akira\QrCode\QrCode size(int $size)
 * @method static \Akira\QrCode\QrCode margin(int $margin)
 * @method static \Akira\QrCode\QrCode color(int $red, int $green, int $blue, int $alpha = 0)
 * @method static \Akira\QrCode\QrCode backgroundColor(int $red, int $green, int $blue, int $alpha = 0)
 * @method static \Akira\QrCode\QrCode errorCorrection(string $level)
 * @method static \Akira\QrCode\QrCode encoding(string $encoding)
 * @method static \Akira\QrCode\QrCode format(string $format)
 * @method static \Akira\QrCode\QrCode merge(string $path, float $percentage = 0.2, bool $absolute = false)
 * @method static \Akira\QrCode\QrCode setData(string $data)
 * @method static string generate(?string $filename = null)
 *
 * @see \Akira\QrCode\QrCode
 */
class QrCode extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor()
    {
        self::clearResolvedInstance(Generator::class);

        return Generator::class;
    }
}
