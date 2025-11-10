<?php

namespace Akira\QrCode\Actions;

use Akira\QrCode\ValueObjects\GeoLocation;

class BuildGeoStringAction
{
    private const PREFIX = 'geo:';

    public function handle(GeoLocation $location): string
    {
        $geo = self::PREFIX . $location->latitude . ',' . $location->longitude;

        if ($location->hasName()) {
            $query = http_build_query(['name' => $location->name]);
            $geo .= '?' . $query;
        }

        return $geo;
    }
}
