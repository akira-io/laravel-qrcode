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
 * @method static \Akira\QrCode\QrCode merge(string $path, ?float $percentage = null, bool $absolute = false)
 * @method static \Akira\QrCode\QrCode mergeString(string $content, ?float $percentage = null)
 * @method static \Akira\QrCode\QrCode style(string $style, float $size = 0.5)
 * @method static \Akira\QrCode\QrCode eye(string $style)
 * @method static \Akira\QrCode\QrCode eyeColor(int $eyeNumber, int $innerRed, int $innerGreen, int $innerBlue, int $outerRed = 0, int $outerGreen = 0, int $outerBlue = 0)
 * @method static \Akira\QrCode\QrCode gradient(int $startRed, int $startGreen, int $startBlue, int $endRed, int $endGreen, int $endBlue, string $type)
 * @method static \BaconQrCode\Renderer\Color\ColorInterface createColor(int $red, int $green, int $blue, ?int $alpha = null)
 * @method static \Akira\QrCode\QrCode cache(?int $ttl = null, ?string $prefix = null)
 * @method static \Akira\QrCode\QrCode withoutCache()
 * @method static string cacheKeyFor(string $text)
 * @method static \Illuminate\Support\HtmlString|string|null generate(string $text, ?string $filename = null)
 * @method static string|null generateRaw(string $text, ?string $filename = null)
 * @method static \Illuminate\Support\Collection<int|string, \Illuminate\Support\HtmlString|string|null> batch(iterable<int|string, string> $texts)
 * @method static \Illuminate\Support\Collection<int|string, string|null> batchRaw(iterable<int|string, string> $texts)
 * @method static \Illuminate\Support\HtmlString|string|null text(string $text)
 * @method static \Illuminate\Support\HtmlString|string|null email(string $address, ?string $subject = null, ?string $body = null, ?string $cc = null, ?string $bcc = null)
 * @method static \Illuminate\Support\HtmlString|string|null wifi(array<string, mixed> $config)
 * @method static \Illuminate\Support\HtmlString|string|null sms(string $phoneNumber, ?string $message = null)
 * @method static \Illuminate\Support\HtmlString|string|null phone(string $phoneNumber)
 * @method static \Illuminate\Support\HtmlString|string|null phoneNumber(string $phoneNumber)
 * @method static \Illuminate\Support\HtmlString|string|null geo(float $latitude, float $longitude, ?string $name = null)
 * @method static \Illuminate\Support\HtmlString|string|null bitcoin(string $address, ?float $amount = null, array<string, mixed> $options = [])
 * @method static \Illuminate\Support\HtmlString|string|null btc(string $address, ?float $amount = null, array<string, mixed> $options = [])
 * @method static \Illuminate\Support\HtmlString|string|null ethereum(string $address, int|float|string|null $value = null, array<string, mixed> $options = [])
 * @method static \Illuminate\Support\HtmlString|string|null eth(string $address, int|float|string|null $value = null, array<string, mixed> $options = [])
 * @method static \Illuminate\Support\HtmlString|string|null litecoin(string $address, float $amount = 0.0, array<string, mixed> $options = [])
 * @method static \Illuminate\Support\HtmlString|string|null ltc(string $address, float $amount = 0.0, array<string, mixed> $options = [])
 * @method static \Illuminate\Support\HtmlString|string|null vcard(array<string, mixed> $contact)
 * @method static \Illuminate\Support\HtmlString|string|null contact(array<string, mixed> $contact)
 * @method static \Illuminate\Support\HtmlString|string|null calendar(array<string, mixed> $event)
 * @method static \Illuminate\Support\HtmlString|string|null ical(array<string, mixed> $event)
 * @method static \Illuminate\Support\HtmlString|string|null event(array<string, mixed> $event)
 */
final class QrCode extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        self::clearResolvedInstance(Generator::class);

        return Generator::class;
    }
}
