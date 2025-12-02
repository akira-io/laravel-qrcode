<?php

declare(strict_types=1);

namespace Akira\QrCode\Actions;

use Akira\QrCode\DataTypes\PhoneNumberDataType;
use Akira\QrCode\ValueObjects\PhoneNumber;

final class CreatePhoneNumberQrCodeAction
{
    public function handle(PhoneNumber $phoneNumber): string
    {
        $dataType = PhoneNumberDataType::fromValueObject($phoneNumber);

        return (string) $dataType;
    }
}
