<?php

declare(strict_types=1);

use Akira\QrCode\DataTypes\VCardDataType;
use Akira\QrCode\Support\DataTypeMapper;
use Akira\QrCode\ValueObjects\VCardData;

it('generates a minimal vCard QR payload', function (): void {
    $vCardData = VCardData::create('John Doe');
    $dataType = VCardDataType::fromValueObject($vCardData);

    expect((string) $dataType)->toBe(implode("\n", [
        'BEGIN:VCARD',
        'VERSION:3.0',
        'FN:John Doe',
        'END:VCARD',
    ]));
});

it('generates a vCard QR payload with contact fields', function (): void {
    $vCardData = VCardData::create(
        fullName: 'John Doe',
        firstName: 'John',
        lastName: 'Doe',
        organization: 'Akira',
        title: 'Engineer',
        phone: '+1 555 0100',
        email: 'john@example.com',
        url: 'https://example.com',
        address: '742 Evergreen Terrace',
        note: "Line one\nLine two"
    );

    $dataType = VCardDataType::fromValueObject($vCardData);

    expect((string) $dataType)->toBe(implode("\n", [
        'BEGIN:VCARD',
        'VERSION:3.0',
        'FN:John Doe',
        'N:Doe;John;;;',
        'ORG:Akira',
        'TITLE:Engineer',
        'TEL:+1 555 0100',
        'EMAIL:john@example.com',
        'URL:https://example.com',
        'ADR:;;742 Evergreen Terrace;;;;',
        'NOTE:Line one\nLine two',
        'END:VCARD',
    ]));
});

it('escapes reserved vCard characters', function (): void {
    $vCardData = VCardData::create('Doe, John', organization: 'Acme; Labs');
    $dataType = VCardDataType::fromValueObject($vCardData);

    expect((string) $dataType)->toContain('FN:Doe\, John')->toContain('ORG:Acme\; Labs');
});

it('maps dynamic vCard calls', function (): void {
    $payload = DataTypeMapper::createFromMethod('vcard', [[
        'fullName' => 'Jane Doe',
        'email' => 'jane@example.com',
    ]]);

    expect($payload)->toContain('FN:Jane Doe')->toContain('EMAIL:jane@example.com');
});

it('throws an exception when vCard full name is empty', function (): void {
    VCardData::create('');
})->throws(InvalidArgumentException::class, 'Full name cannot be empty');

it('throws an exception when vCard email is invalid', function (): void {
    VCardData::create('John Doe', email: 'invalid');
})->throws(InvalidArgumentException::class, 'Invalid email address');
