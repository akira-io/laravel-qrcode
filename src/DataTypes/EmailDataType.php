<?php

namespace Akira\QrCode\DataTypes;

use Akira\QrCode\Actions\BuildEmailStringAction;
use Akira\QrCode\Contracts\QrCodeDataTypeContract;
use Akira\QrCode\ValueObjects\EmailData;

final readonly class EmailDataType implements QrCodeDataTypeContract
{
    public function __construct(
        private EmailData $data,
        private BuildEmailStringAction $action
    ) {}

    public static function fromValueObject(EmailData $data): self
    {
        return app(self::class, ['data' => $data]);
    }

    public function __toString(): string
    {
        return $this->action->handle($this->data);
    }
}
