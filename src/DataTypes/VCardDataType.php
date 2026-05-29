<?php

declare(strict_types=1);

namespace Akira\QrCode\DataTypes;

use Akira\QrCode\Actions\BuildVCardStringAction;
use Akira\QrCode\Contracts\QrCodeDataTypeContract;
use Akira\QrCode\ValueObjects\VCardData;

final readonly class VCardDataType implements QrCodeDataTypeContract
{
    public function __construct(
        private VCardData $data,
        private BuildVCardStringAction $action
    ) {}

    public function __toString(): string
    {
        return $this->action->handle($this->data);
    }

    public static function fromValueObject(VCardData $data): self
    {
        return resolve(self::class, ['data' => $data]);
    }
}
