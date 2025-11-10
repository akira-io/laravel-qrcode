<?php

namespace Akira\QrCode\Actions;

use Akira\QrCode\DataTypes\PhoneNumberDataType;
use Akira\QrCode\ValueObjects\PhoneNumber;

class CreatePhoneNumberQrCodeAction
{
    public function handle(PhoneNumber $phoneNumber): string
    {
        $dataType = PhoneNumberDataType::fromValueObject($phoneNumber);

        return (string) $dataType;
    }
}
