<?php

declare(strict_types=1);

namespace Akira\QrCode\Actions;

use Akira\QrCode\ValueObjects\GeoLocation;

final class BuildGeoStringAction
{
    private const string PREFIX = 'geo:';

    public function handle(GeoLocation $location): string
    {
        $geo = self::PREFIX.$location->latitude.','.$location->longitude;

        if ($location->hasName()) {
            $query = http_build_query(['name' => $location->name]);
            $geo .= '?'.$query;
        }

        return $geo;
    }
}
