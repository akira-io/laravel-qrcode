<?php

declare(strict_types=1);

namespace Akira\QrCode\Actions;

use Akira\QrCode\ValueObjects\CalendarEventData;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;

final class BuildCalendarEventStringAction
{
    public function handle(CalendarEventData $data): string
    {
        $lines = [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:-//Akira//Laravel QR Code//EN',
            'BEGIN:VEVENT',
            'UID:'.$this->uniqueId($data),
            'DTSTAMP:'.$this->formatDate($data->timestamp),
            'DTSTART:'.$this->formatDate($data->startsAt),
            'DTEND:'.$this->formatDate($data->endsAt),
            'SUMMARY:'.$this->escape($data->summary),
        ];

        $this->appendOptionalLine($lines, 'LOCATION', $data->location);
        $this->appendOptionalLine($lines, 'DESCRIPTION', $data->description);

        $lines[] = 'END:VEVENT';
        $lines[] = 'END:VCALENDAR';

        return implode("\n", $lines);
    }

    /**
     * @param  array<int, string>  $lines
     */
    private function appendOptionalLine(array &$lines, string $name, ?string $value): void
    {
        if ($value === null || $value === '') {
            return;
        }

        $lines[] = $name.':'.$this->escape($value);
    }

    private function uniqueId(CalendarEventData $data): string
    {
        if ($data->uniqueId !== null && $data->uniqueId !== '') {
            return $this->escape($data->uniqueId);
        }

        return sha1($data->summary.$data->startsAt->format(DateTimeInterface::ATOM)).'@akira-laravel-qrcode';
    }

    private function formatDate(DateTimeInterface $date): string
    {
        return DateTimeImmutable::createFromInterface($date)
            ->setTimezone(new DateTimeZone('UTC'))
            ->format('Ymd\THis\Z');
    }

    private function escape(string $value): string
    {
        return strtr($value, [
            '\\' => '\\\\',
            "\n" => '\n',
            "\r" => '',
            ';' => '\;',
            ',' => '\,',
        ]);
    }
}
