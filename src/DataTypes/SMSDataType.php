<?php

namespace Akira\QrCode\DataTypes;

use Akira\QrCode\Actions\BuildSMSStringAction;
use Akira\QrCode\Contracts\QrCodeDataTypeContract;
use Akira\QrCode\ValueObjects\SMSData;

final readonly class SMSDataType implements QrCodeDataTypeContract
{
    public function __construct(
        private SMSData $data,
        private BuildSMSStringAction $action
    ) {}

    public static function fromValueObject(SMSData $data): self
    {
        return app(self::class, ['data' => $data]);
    }

    public function __toString(): string
    {
        return $this->action->handle($this->data);
    }
}
