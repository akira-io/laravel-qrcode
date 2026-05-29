<?php

declare(strict_types=1);

namespace Akira\QrCode\Actions;

use Akira\QrCode\ValueObjects\WiFiData;

final class BuildWiFiStringAction
{
    private const string PREFIX = 'WIFI:';

    private const string SEPARATOR = ';';

    public function handle(WiFiData $data): string
    {
        $wifi = self::PREFIX.'T:'.$data->encryptionType().self::SEPARATOR;

        if ($data->ssid !== '' && $data->ssid !== '0') {
            $wifi .= 'S:'.$this->escape($data->ssid).self::SEPARATOR;
        }

        if ($data->hasPassword()) {
            $wifi .= 'P:'.$this->escape((string) $data->password).self::SEPARATOR;
        }

        if ($data->hidden) {
            $wifi .= 'H:true'.self::SEPARATOR;
        }

        return $wifi.self::SEPARATOR;
    }

    private function escape(string $value): string
    {
        return strtr($value, [
            '\\' => '\\\\',
            ';' => '\;',
            ',' => '\,',
            ':' => '\:',
        ]);
    }
}
