<?php

declare(strict_types=1);

namespace Akira\QrCode\Actions;

use Akira\QrCode\ValueObjects\SMSData;

final class BuildSMSStringAction
{
    private const string PREFIX = 'SMSTO:';

    private const string SEPARATOR = ':';

    public function handle(SMSData $data): string
    {
        $sms = self::PREFIX.$data->phoneNumber;

        if ($data->hasMessage()) {
            $sms .= self::SEPARATOR.rawurlencode((string) $data->message);
        }

        return $sms;
    }
}
