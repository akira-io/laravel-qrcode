<?php

declare(strict_types=1);

namespace Akira\QrCode\ValueObjects;

use InvalidArgumentException;

final readonly class GeoLocation
{
    public function __construct(
        public float $latitude,
        public float $longitude,
        public ?string $name = null
    ) {
        throw_if($latitude < -90 || $latitude > 90, InvalidArgumentException::class, "Latitude must be between -90 and 90, got {$latitude}");

        throw_if($longitude < -180 || $longitude > 180, InvalidArgumentException::class, "Longitude must be between -180 and 180, got {$longitude}");
    }

    public static function create(float $latitude, float $longitude, ?string $name = null): self
    {
        return new self($latitude, $longitude, $name);
    }

    public function hasName(): bool
    {
        return $this->name !== null && $this->name !== '';
    }
}
