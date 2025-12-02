<?php

declare(strict_types=1);

use Akira\QrCode\DataTypes\EmailDataType;
use Akira\QrCode\ValueObjects\EmailData;

it('should generate a valid email QR code', function (): void {
    $emailData = EmailData::create('email@example.com');
    $dataType = EmailDataType::fromValueObject($emailData);

    expect((string) $dataType)->toBe('mailto:email@example.com');
});

it('should generate a valid email QR code with subject', function (): void {
    $emailData = EmailData::create('email@example.com', 'subject');
    $dataType = EmailDataType::fromValueObject($emailData);

    expect((string) $dataType)->toBe('mailto:email@example.com?subject=subject');
});

it('should generate a valid email QR code with subject and body', function (): void {
    $emailData = EmailData::create('email@example.com', 'subject', 'body');
    $dataType = EmailDataType::fromValueObject($emailData);

    expect((string) $dataType)->toBe('mailto:email@example.com?subject=subject&body=body');
});

it('throws an exception when the email is invalid', function (): void {
    EmailData::create('invalid-email');
})->throws(InvalidArgumentException::class, 'Invalid email address');

it('should generate a valid email QR code with cc', function (): void {
    $emailData = EmailData::create('email@example.com', 'subject', 'body', 'cc@example.com');
    $dataType = EmailDataType::fromValueObject($emailData);

    expect((string) $dataType)->toBe('mailto:email@example.com?subject=subject&body=body&cc=cc%40example.com');
});

it('should generate a valid email QR code with cc and bcc', function (): void {
    $emailData = EmailData::create('email@example.com', 'subject', 'body', 'cc@example.com', 'bcc@example.com');
    $dataType = EmailDataType::fromValueObject($emailData);

    expect((string) $dataType)->toBe('mailto:email@example.com?subject=subject&body=body&cc=cc%40example.com&bcc=bcc%40example.com');
});
