<?php

declare(strict_types=1);

namespace Akira\QrCode\DataTypes;

use Akira\QrCode\Actions\BuildWiFiStringAction;
use Akira\QrCode\Contracts\QrCodeDataTypeContract;
use Akira\QrCode\ValueObjects\WiFiData;

final readonly class WiFiDataType implements QrCodeDataTypeContract
{
    public function __construct(
        private WiFiData $data,
        private BuildWiFiStringAction $action
    ) {}

    public function __toString(): string
    {
        return $this->action->handle($this->data);
    }

    public static function fromValueObject(WiFiData $data): self
    {
        return resolve(self::class, ['data' => $data]);
    }
}
