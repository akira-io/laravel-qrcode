<?php

declare(strict_types=1);

namespace Akira\QrCode\Support;

use Akira\QrCode\DataTypes\CalendarEventDataType;
use Akira\QrCode\DataTypes\VCardDataType;
use Akira\QrCode\ValueObjects\CalendarEventData;
use Akira\QrCode\ValueObjects\VCardData;
use DateTimeImmutable;
use DateTimeInterface;
use Illuminate\Support\Fluent;
use InvalidArgumentException;
use Throwable;

final class ContactEventDataTypeMapper
{
    /**
     * @param  array<int, mixed>  $arguments
     */
    public static function createVCard(array $arguments): string
    {
        $data = $arguments[0] ?? null;

        throw_unless(is_array($data), InvalidArgumentException::class, 'vCard requires an array argument.');

        $contact = new Fluent($data);
        $fullName = $contact->get('fullName', $contact->get('name', $contact->get('fn', '')));

        $vCardData = VCardData::create(
            fullName: self::stringOrEmpty($fullName),
            firstName: self::stringOrNull($contact->get('firstName')),
            lastName: self::stringOrNull($contact->get('lastName')),
            organization: self::stringOrNull($contact->get('organization')),
            title: self::stringOrNull($contact->get('title')),
            phone: self::stringOrNull($contact->get('phone')),
            email: self::stringOrNull($contact->get('email')),
            url: self::stringOrNull($contact->get('url')),
            address: self::stringOrNull($contact->get('address')),
            note: self::stringOrNull($contact->get('note')),
        );

        return (string) VCardDataType::fromValueObject($vCardData);
    }

    /**
     * @param  array<int, mixed>  $arguments
     */
    public static function createCalendarEvent(array $arguments): string
    {
        $data = $arguments[0] ?? null;

        throw_unless(is_array($data), InvalidArgumentException::class, 'Calendar event requires an array argument.');

        $event = new Fluent($data);
        $startsAt = $event->get('startsAt', $event->get('start', $event->get('startAt')));
        $endsAt = $event->get('endsAt', $event->get('end', $event->get('endAt')));

        $calendarEventData = CalendarEventData::create(
            summary: self::stringOrEmpty($event->get('summary')),
            startsAt: self::dateTime($startsAt, 'start date'),
            endsAt: self::dateTime($endsAt, 'end date'),
            location: self::stringOrNull($event->get('location')),
            description: self::stringOrNull($event->get('description')),
            uniqueId: self::stringOrNull($event->get('uid')),
            timestamp: self::dateTimeOrNull($event->get('timestamp')),
        );

        return (string) CalendarEventDataType::fromValueObject($calendarEventData);
    }

    private static function stringOrEmpty(mixed $value): string
    {
        return is_string($value) ? $value : '';
    }

    private static function stringOrNull(mixed $value): ?string
    {
        return is_string($value) ? $value : null;
    }

    private static function dateTime(mixed $value, string $field): DateTimeInterface
    {
        if ($value instanceof DateTimeInterface) {
            return $value;
        }

        if (is_string($value) && $value !== '') {
            try {
                return new DateTimeImmutable($value);
            } catch (Throwable $exception) {
                throw new InvalidArgumentException("Calendar event {$field} must be a valid date", $exception->getCode(), previous: $exception);
            }
        }

        throw new InvalidArgumentException("Calendar event {$field} must be a valid date");
    }

    private static function dateTimeOrNull(mixed $value): ?DateTimeInterface
    {
        if ($value === null) {
            return null;
        }

        return self::dateTime($value, 'timestamp');
    }
}
