<?php

declare(strict_types=1);

namespace Akira\QrCode\Concerns;

use InvalidArgumentException;
use Throwable;

trait AppliesConfiguredOptions
{
    public function preset(string $name): self
    {
        return $this->applyConfiguredOptions("qrcode.presets.{$name}", "QR code preset [{$name}] is not configured.");
    }

    public function theme(string $name): self
    {
        return $this->applyConfiguredOptions("qrcode.themes.{$name}", "QR code theme [{$name}] is not configured.");
    }

    private function applyConfiguredOptions(string $key, string $missingMessage): self
    {
        $options = $this->configuredOptions($key);

        throw_if($options === null, InvalidArgumentException::class, $missingMessage);

        $this->applyOptions($options);

        return $this;
    }

    /**
     * @param  array<string, mixed>  $options
     */
    private function applyOptions(array $options): void
    {
        $this->applyStringOption($options, 'format', 'format');
        $this->applyIntOption($options, 'size', 'size');
        $this->applyIntOption($options, 'margin', 'margin');
        $this->applyStringOption($options, 'encoding', 'encoding');
        $this->applyStringOption($options, 'error_correction', 'errorCorrection');
        $this->applyStringOption($options, 'style', 'style');
        $this->applyStringOption($options, 'eye', 'eye');
        $this->applyColorOption($options['color'] ?? null, 'color');
        $this->applyColorOption($options['background_color'] ?? null, 'backgroundColor');
    }

    /**
     * @param  array<string, mixed>  $options
     */
    private function applyStringOption(array $options, string $key, string $method): void
    {
        $value = $options[$key] ?? null;

        if (is_string($value) && $value !== '') {
            $this->{$method}($value);
        }
    }

    /**
     * @param  array<string, mixed>  $options
     */
    private function applyIntOption(array $options, string $key, string $method): void
    {
        $value = $options[$key] ?? null;

        if (is_numeric($value)) {
            $this->{$method}((int) $value);
        }
    }

    private function applyColorOption(mixed $color, string $method): void
    {
        if ($color === null) {
            return;
        }

        throw_unless(is_array($color), InvalidArgumentException::class, 'Configured QR code colors must be arrays.');

        $red = $color[0] ?? null;
        $green = $color[1] ?? null;
        $blue = $color[2] ?? null;
        $alpha = $color[3] ?? null;

        throw_unless(is_numeric($red) && is_numeric($green) && is_numeric($blue), InvalidArgumentException::class, 'Configured QR code colors must include red, green, and blue values.');

        $this->{$method}(
            (int) $red,
            (int) $green,
            (int) $blue,
            is_numeric($alpha) ? (int) $alpha : null
        );
    }

    /**
     * @return array<string, mixed>|null
     */
    private function configuredOptions(string $key): ?array
    {
        if (! function_exists('config')) {
            return null;
        }

        try {
            $configuredOptions = config($key);
        } catch (Throwable) {
            return null;
        }

        if (! is_array($configuredOptions)) {
            return null;
        }

        $namedOptions = [];

        foreach ($configuredOptions as $optionKey => $optionValue) {
            if (is_string($optionKey)) {
                $namedOptions[$optionKey] = $optionValue;
            }
        }

        return $namedOptions;
    }
}
