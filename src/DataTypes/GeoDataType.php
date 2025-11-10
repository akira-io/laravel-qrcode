<?php

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

    public static function fromValueObject(GeoLocation $location): self
    {
        return app(self::class, ['location' => $location]);
    }


    public function __toString(): string
    {
        return $this->action->handle($this->location);
    }
}
