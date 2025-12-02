<?php

declare(strict_types=1);

namespace Akira\QrCode\Actions;

use Akira\QrCode\ValueObjects\PhoneNumber;

final class BuildPhoneNumberStringAction
{
    private const string PREFIX = 'tel:';

    public function handle(PhoneNumber $phoneNumber): string
    {
        return self::PREFIX.$phoneNumber->number;
    }
}
