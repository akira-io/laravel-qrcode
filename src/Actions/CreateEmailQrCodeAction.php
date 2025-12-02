<?php

declare(strict_types=1);

namespace Akira\QrCode\Actions;

use Akira\QrCode\DataTypes\EmailDataType;
use Akira\QrCode\ValueObjects\EmailData;

final class CreateEmailQrCodeAction
{
    public function handle(EmailData $data): string
    {
        $dataType = EmailDataType::fromValueObject($data);

        return (string) $dataType;
    }
}
