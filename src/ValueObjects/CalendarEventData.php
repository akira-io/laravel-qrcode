<?php

declare(strict_types=1);

namespace Akira\QrCode\ValueObjects;

use DateTimeImmutable;
use DateTimeInterface;
use InvalidArgumentException;

final readonly class CalendarEventData
{
    public function __construct(
        public string $summary,
        public DateTimeInterface $startsAt,
        public DateTimeInterface $endsAt,
        public ?string $location = null,
        public ?string $description = null,
        public ?string $uniqueId = null,
        public DateTimeInterface $timestamp = new DateTimeImmutable
    ) {
        throw_if($summary === '' || $summary === '0', InvalidArgumentException::class, 'Summary cannot be empty');

        throw_unless($endsAt > $startsAt, InvalidArgumentException::class, 'End date must be after start date');
    }

    public static function create(
        string $summary,
        DateTimeInterface $startsAt,
        DateTimeInterface $endsAt,
        ?string $location = null,
        ?string $description = null,
        ?string $uniqueId = null,
        ?DateTimeInterface $timestamp = null
    ): self {
        if (! $timestamp instanceof DateTimeInterface) {
            return new self($summary, $startsAt, $endsAt, $location, $description, $uniqueId);
        }

        return new self($summary, $startsAt, $endsAt, $location, $description, $uniqueId, $timestamp);
    }
}
