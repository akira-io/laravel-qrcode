<?php

namespace Akira\QrCode\ValueObjects;

use InvalidArgumentException;

final readonly class ImageMergeConfig
{
    public function __construct(
        public string $filepath,
        public float $percentage = 0.2,
        public bool $absolute = false
    ) {
        if ($percentage <= 0 || $percentage > 1) {
            throw new InvalidArgumentException(
                "Percentage must be between 0 and 1, got {$percentage}"
            );
        }

        if (empty($filepath)) {
            throw new InvalidArgumentException('Filepath cannot be empty');
        }
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
            return base_path($this->filepath);
        }

        return $this->filepath;
    }
}
