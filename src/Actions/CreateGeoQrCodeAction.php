<?php

declare(strict_types=1);

namespace Akira\QrCode\Actions;

use Akira\QrCode\DataTypes\GeoDataType;
use Akira\QrCode\ValueObjects\GeoLocation;

final class CreateGeoQrCodeAction
{
    public function handle(GeoLocation $location): string
    {
        $dataType = GeoDataType::fromValueObject($location);

        return (string) $dataType;
    }
}
