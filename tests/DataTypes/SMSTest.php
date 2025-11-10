<?php

use Akira\QrCode\DataTypes\SMSDataType;
use Akira\QrCode\ValueObjects\SMSData;

it('should generate a valid SMS QR code', function () {
    $smsData = SMSData::create('555-555-5555');
    $dataType = SMSDataType::fromValueObject($smsData);

    expect((string) $dataType)->toBe('SMSTO:555-555-5555');
});

it('should generate a valid SMS QR code with message', function () {
    $smsData = SMSData::create('555-555-5555', 'message');
    $dataType = SMSDataType::fromValueObject($smsData);

    expect((string) $dataType)->toBe('SMSTO:555-555-5555:message');
});

it('throws an exception when phone number is empty', function () {
    SMSData::create('');
})->throws(InvalidArgumentException::class, 'Phone number cannot be empty');

it('throws an exception when phone number format is invalid', function () {
    SMSData::create('invalid');
})->throws(InvalidArgumentException::class, 'Invalid phone number format');
