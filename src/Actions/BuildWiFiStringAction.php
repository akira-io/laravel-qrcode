<?php

namespace Akira\QrCode\Actions;

use Akira\QrCode\ValueObjects\WiFiData;

class BuildWiFiStringAction
{
    private const string PREFIX = 'WIFI:';

    private const string SEPARATOR = ';';

    public function handle(WiFiData $data): string
    {
        $wifi = self::PREFIX;

        if ($data->hasPassword()) {
            $wifi .= 'T:WPA'.self::SEPARATOR;
        }

        if (! empty($data->ssid)) {
            $wifi .= 'S:'.$data->ssid.self::SEPARATOR;
        }

        if ($data->hasPassword()) {
            $wifi .= 'P:'.$data->password.self::SEPARATOR;
        }

        if ($data->hidden) {
            $wifi .= 'H:true'.self::SEPARATOR;
        }

        return $wifi;
    }
}
