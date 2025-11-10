<?php

namespace Akira\QrCode\Actions;

use Akira\QrCode\ValueObjects\SMSData;

class BuildSMSStringAction
{
    private const PREFIX = 'SMSTO:';
    private const SEPARATOR = ':';

    public function handle(SMSData $data): string
    {
        $sms = self::PREFIX . $data->phoneNumber;

        if ($data->hasMessage()) {
            $sms .= self::SEPARATOR . rawurlencode((string) $data->message);
        }

        return $sms;
    }
}
