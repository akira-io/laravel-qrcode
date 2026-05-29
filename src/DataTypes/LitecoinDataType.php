<?php

declare(strict_types=1);

namespace Akira\QrCode\DataTypes;

use Akira\QrCode\Actions\BuildLitecoinStringAction;
use Akira\QrCode\Contracts\QrCodeDataTypeContract;
use Akira\QrCode\ValueObjects\LitecoinData;

final readonly class LitecoinDataType implements QrCodeDataTypeContract
{
    public function __construct(
        private LitecoinData $data,
        private BuildLitecoinStringAction $action
    ) {}

    public function __toString(): string
    {
        return $this->action->handle($this->data);
    }

    public static function fromValueObject(LitecoinData $data): self
    {
        return resolve(self::class, ['data' => $data]);
    }
}
