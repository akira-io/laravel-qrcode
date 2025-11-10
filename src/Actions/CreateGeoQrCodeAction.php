<?php

namespace Akira\QrCode\Actions;

use Akira\QrCode\DataTypes\GeoDataType;
use Akira\QrCode\ValueObjects\GeoLocation;

class CreateGeoQrCodeAction
{
    public function handle(GeoLocation $location): string
    {
        $dataType = GeoDataType::fromValueObject($location);
        return (string) $dataType;
    }
}
