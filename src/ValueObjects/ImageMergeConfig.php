<?php

declare(strict_types=1);

namespace Akira\QrCode\ValueObjects;

use Error;
use InvalidArgumentException;

final readonly class ImageMergeConfig
{
    public function __construct(
        public string $filepath,
        public float $percentage = 0.2,
        public bool $absolute = false
    ) {
        throw_if($percentage <= 0 || $percentage > 1, InvalidArgumentException::class, "Percentage must be between 0 and 1, got {$percentage}");

        throw_if($filepath === '' || $filepath === '0', InvalidArgumentException::class, 'Filepath cannot be empty');
    }

    public static function create(string $filepath, float $percentage = 0.2, bool $absolute = false): self
    {
        return new self($filepath, $percentage, $absolute);
    }

    public function getFullPath(): string
    {
        if ($this->absolute) {
            return $this->filepath;
        }

        if (function_exists('base_path')) {
            try {
                return base_path($this->filepath);
            } catch (Error) {
                return $this->filepath;
            }
        }

        return $this->filepath;
    }
}
