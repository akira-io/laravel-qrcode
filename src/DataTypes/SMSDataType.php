<?php

declare(strict_types=1);

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

    public function __toString(): string
    {
        return $this->action->handle($this->data);
    }

    public static function fromValueObject(SMSData $data): self
    {
        return resolve(self::class, ['data' => $data]);
    }
}
