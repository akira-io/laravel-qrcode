<?php

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

    public static function fromValueObject(PhoneNumber $phoneNumber): self
    {
        return app(self::class, ['phoneNumber' => $phoneNumber]);
    }

    public function __toString(): string
    {
        return $this->action->handle($this->phoneNumber);
    }
}
