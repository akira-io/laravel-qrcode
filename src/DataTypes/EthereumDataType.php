<?php

declare(strict_types=1);

namespace Akira\QrCode\DataTypes;

use Akira\QrCode\Actions\BuildEthereumStringAction;
use Akira\QrCode\Contracts\QrCodeDataTypeContract;
use Akira\QrCode\ValueObjects\EthereumData;

final readonly class EthereumDataType implements QrCodeDataTypeContract
{
    public function __construct(
        private EthereumData $data,
        private BuildEthereumStringAction $action
    ) {}

    public function __toString(): string
    {
        return $this->action->handle($this->data);
    }

    public static function fromValueObject(EthereumData $data): self
    {
        return resolve(self::class, ['data' => $data]);
    }
}
