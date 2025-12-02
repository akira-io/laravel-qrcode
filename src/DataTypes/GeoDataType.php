<?php

declare(strict_types=1);

namespace Akira\QrCode\DataTypes;

use Akira\QrCode\Actions\BuildGeoStringAction;
use Akira\QrCode\Contracts\QrCodeDataTypeContract;
use Akira\QrCode\ValueObjects\GeoLocation;

final readonly class GeoDataType implements QrCodeDataTypeContract
{
    public function __construct(
        private GeoLocation $location,
        private BuildGeoStringAction $action
    ) {}

    public function __toString(): string
    {
        return $this->action->handle($this->location);
    }

    public static function fromValueObject(GeoLocation $location): self
    {
        return resolve(self::class, ['location' => $location]);
    }
}
