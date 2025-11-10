<?php

declare(strict_types=1);

namespace Akira\QrCode\Facades;

use Akira\QrCode\QrCode as Generator;
use Illuminate\Support\Facades\Facade;

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
 * @method static \Illuminate\Support\HtmlString|string|null generate(string $text, ?string $filename = null)
 * @method static \Illuminate\Support\HtmlString|string|null text(string $text)
 * @method static \Illuminate\Support\HtmlString|string|null email(string $address, ?string $subject = null, ?string $body = null, ?string $cc = null, ?string $bcc = null)
 * @method static \Illuminate\Support\HtmlString|string|null wifi(array<string, mixed> $config)
 * @method static \Illuminate\Support\HtmlString|string|null sms(string $phoneNumber, ?string $message = null)
 * @method static \Illuminate\Support\HtmlString|string|null phone(string $phoneNumber)
 * @method static \Illuminate\Support\HtmlString|string|null phoneNumber(string $phoneNumber)
 * @method static \Illuminate\Support\HtmlString|string|null geo(float $latitude, float $longitude, ?string $name = null)
 * @method static \Illuminate\Support\HtmlString|string|null bitcoin(string $address, float $amount = 0.0, array<string, mixed> $options = [])
 * @method static \Illuminate\Support\HtmlString|string|null btc(string $address, float $amount = 0.0, array<string, mixed> $options = [])
 *
 * @see \Akira\QrCode\QrCode
 */
class QrCode extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        self::clearResolvedInstance(Generator::class);

        return Generator::class;
    }
}
