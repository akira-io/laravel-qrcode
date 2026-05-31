<?php

declare(strict_types=1);

namespace Akira\QrCode\DataTypes;

use Akira\QrCode\Actions\BuildCalendarEventStringAction;
use Akira\QrCode\Contracts\QrCodeDataTypeContract;
use Akira\QrCode\ValueObjects\CalendarEventData;

final readonly class CalendarEventDataType implements QrCodeDataTypeContract
{
    public function __construct(
        private CalendarEventData $data,
        private BuildCalendarEventStringAction $action
    ) {}

    public function __toString(): string
    {
        return $this->action->handle($this->data);
    }

    public static function fromValueObject(CalendarEventData $data): self
    {
        return resolve(self::class, ['data' => $data]);
    }
}
