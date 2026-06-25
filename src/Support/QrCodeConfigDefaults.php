<?php

declare(strict_types=1);

namespace Akira\QrCode\Support;

use Akira\QrCode\QrCode;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Throwable;

final class QrCodeConfigDefaults
{
    public static function apply(QrCode $qrCode, string $format, int $size, int $margin, string $encoding): void
    {
        $config = self::repository();

        if (! $config instanceof ConfigRepository) {
            return;
        }

        $qrCode->format(self::string($config, 'qrcode.format', $format));
        $qrCode->size(self::int($config, 'qrcode.size', $size));
        $qrCode->margin(self::int($config, 'qrcode.margin', $margin));
        $qrCode->encoding(self::string($config, 'qrcode.encoding', $encoding));
        self::applyErrorCorrection($qrCode, $config);
        self::applyColor($qrCode, $config, 'qrcode.color', 'color');
        self::applyColor($qrCode, $config, 'qrcode.background_color', 'backgroundColor');
        self::applyCache($qrCode, $config);
    }

    public static function mergePercentage(float $default): float
    {
        $config = self::repository();

        if (! $config instanceof ConfigRepository) {
            return $default;
        }

        $percentage = $config->get('qrcode.merge.percentage', $default);

        return is_numeric($percentage) ? (float) $percentage : $default;
    }

    private static function repository(): ?ConfigRepository
    {
        if (! function_exists('app')) {
            return null;
        }

        try {
            $app = app();
        } catch (Throwable) {
            return null;
        }

        try {
            return $app->make(ConfigRepository::class);
        } catch (Throwable) {
            return null;
        }
    }

    private static function string(ConfigRepository $config, string $key, string $default): string
    {
        $configuredValue = $config->get($key, $default);

        return is_string($configuredValue) ? $configuredValue : $default;
    }

    private static function int(ConfigRepository $config, string $key, int $default): int
    {
        $configuredValue = $config->get($key, $default);

        return is_numeric($configuredValue) ? (int) $configuredValue : $default;
    }

    private static function bool(ConfigRepository $config, string $key, bool $default): bool
    {
        $configuredValue = $config->get($key, $default);

        if (is_bool($configuredValue)) {
            return $configuredValue;
        }

        if (is_string($configuredValue)) {
            return filter_var($configuredValue, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) ?? $default;
        }

        if (is_numeric($configuredValue)) {
            return (bool) $configuredValue;
        }

        return $default;
    }

    private static function applyErrorCorrection(QrCode $qrCode, ConfigRepository $config): void
    {
        $errorCorrection = $config->get('qrcode.error_correction');

        if (! is_string($errorCorrection) || $errorCorrection === '') {
            return;
        }

        $qrCode->errorCorrection($errorCorrection);
    }

    private static function applyColor(QrCode $qrCode, ConfigRepository $config, string $key, string $method): void
    {
        $color = $config->get($key);

        if (! is_array($color)) {
            return;
        }

        $red = $color[0] ?? null;
        $green = $color[1] ?? null;
        $blue = $color[2] ?? null;
        $alpha = $color[3] ?? null;

        if (! is_numeric($red) || ! is_numeric($green) || ! is_numeric($blue)) {
            return;
        }

        $qrCode->{$method}(
            (int) $red,
            (int) $green,
            (int) $blue,
            is_numeric($alpha) ? (int) $alpha : null
        );
    }

    private static function applyCache(QrCode $qrCode, ConfigRepository $config): void
    {
        if (! self::bool($config, 'qrcode.cache.enabled', false)) {
            $qrCode->withoutCache();

            return;
        }

        $qrCode->cache(
            self::int($config, 'qrcode.cache.ttl', 3600),
            self::string($config, 'qrcode.cache.prefix', 'qrcode')
        );
    }
}
