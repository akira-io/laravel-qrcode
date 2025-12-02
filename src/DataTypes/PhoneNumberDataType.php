<?php

declare(strict_types=1);

namespace Akira\QrCode\DataTypes;

use Akira\QrCode\Actions\BuildPhoneNumberStringAction;
use Akira\QrCode\Contracts\QrCodeDataTypeContract;
use Akira\QrCode\ValueObjects\PhoneNumber;

final readonly class PhoneNumberDataType implements QrCodeDataTypeContract
{
    public function __construct(
        private PhoneNumber $phoneNumber,
        private BuildPhoneNumberStringAction $action
    ) {}

    public function __toString(): string
    {
        return $this->action->handle($this->phoneNumber);
    }

    public static function fromValueObject(PhoneNumber $phoneNumber): self
    {
        return resolve(self::class, ['phoneNumber' => $phoneNumber]);
    }
}
