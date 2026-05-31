<?php

declare(strict_types=1);

use Akira\QrCode\DataTypes\CalendarEventDataType;
use Akira\QrCode\Support\DataTypeMapper;
use Akira\QrCode\ValueObjects\CalendarEventData;

it('generates a VEVENT QR payload', function (): void {
    $eventData = CalendarEventData::create(
        summary: 'Release planning',
        startsAt: new DateTimeImmutable('2026-06-01 10:00:00', new DateTimeZone('UTC')),
        endsAt: new DateTimeImmutable('2026-06-01 11:00:00', new DateTimeZone('UTC')),
        location: 'HQ',
        description: 'Plan v1.3.0',
        uniqueId: 'release-planning@example.com',
        timestamp: new DateTimeImmutable('2026-05-29 12:00:00', new DateTimeZone('UTC'))
    );

    $dataType = CalendarEventDataType::fromValueObject($eventData);

    expect((string) $dataType)->toBe(implode("\n", [
        'BEGIN:VCALENDAR',
        'VERSION:2.0',
        'PRODID:-//Akira//Laravel QR Code//EN',
        'BEGIN:VEVENT',
        'UID:release-planning@example.com',
        'DTSTAMP:20260529T120000Z',
        'DTSTART:20260601T100000Z',
        'DTEND:20260601T110000Z',
        'SUMMARY:Release planning',
        'LOCATION:HQ',
        'DESCRIPTION:Plan v1.3.0',
        'END:VEVENT',
        'END:VCALENDAR',
    ]));
});

it('escapes reserved VEVENT characters', function (): void {
    $eventData = CalendarEventData::create(
        summary: 'Planning, review',
        startsAt: new DateTimeImmutable('2026-06-01 10:00:00', new DateTimeZone('UTC')),
        endsAt: new DateTimeImmutable('2026-06-01 11:00:00', new DateTimeZone('UTC')),
        description: "Line one\nLine two; done",
        timestamp: new DateTimeImmutable('2026-05-29 12:00:00', new DateTimeZone('UTC'))
    );

    $dataType = CalendarEventDataType::fromValueObject($eventData);

    expect((string) $dataType)
        ->toContain('SUMMARY:Planning\, review')
        ->toContain('DESCRIPTION:Line one\nLine two\; done');
});

it('maps dynamic calendar event calls', function (): void {
    $payload = DataTypeMapper::createFromMethod('ical', [[
        'summary' => 'Demo',
        'startsAt' => '2026-06-01 10:00:00 UTC',
        'endsAt' => '2026-06-01 11:00:00 UTC',
        'timestamp' => '2026-05-29 12:00:00 UTC',
        'uid' => 'demo@example.com',
    ]]);

    expect($payload)->toContain('UID:demo@example.com')->toContain('SUMMARY:Demo');
});

it('throws an exception when calendar event summary is empty', function (): void {
    CalendarEventData::create(
        summary: '',
        startsAt: new DateTimeImmutable('2026-06-01 10:00:00', new DateTimeZone('UTC')),
        endsAt: new DateTimeImmutable('2026-06-01 11:00:00', new DateTimeZone('UTC'))
    );
})->throws(InvalidArgumentException::class, 'Summary cannot be empty');

it('throws an exception when calendar event end date is before start date', function (): void {
    CalendarEventData::create(
        summary: 'Demo',
        startsAt: new DateTimeImmutable('2026-06-01 11:00:00', new DateTimeZone('UTC')),
        endsAt: new DateTimeImmutable('2026-06-01 10:00:00', new DateTimeZone('UTC'))
    );
})->throws(InvalidArgumentException::class, 'End date must be after start date');
