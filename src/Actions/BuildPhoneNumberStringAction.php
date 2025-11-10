<?php

namespace Akira\QrCode\Actions;

use Akira\QrCode\ValueObjects\PhoneNumber;

class BuildPhoneNumberStringAction
{
    private const PREFIX = 'tel:';

    public function handle(PhoneNumber $phoneNumber): string
    {
        return self::PREFIX . $phoneNumber->number;
    }
}
