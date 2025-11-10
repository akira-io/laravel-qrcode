<?php

use Akira\QrCode\DataTypes\PhoneNumberDataType;
use Akira\QrCode\ValueObjects\PhoneNumber;

it('should generate a valid phone number QR code', function () {
    $phoneNumber = PhoneNumber::fromString('+1234567890');
    $dataType = PhoneNumberDataType::fromValueObject($phoneNumber);

    expect((string) $dataType)->toBe('tel:+1234567890');
});

it('throws an exception when phone number is empty', function () {
    PhoneNumber::fromString('');
})->throws(InvalidArgumentException::class, 'Phone number cannot be empty');

it('throws an exception when phone number format is invalid', function () {
    PhoneNumber::fromString('invalid');
})->throws(InvalidArgumentException::class, 'Invalid phone number format');
