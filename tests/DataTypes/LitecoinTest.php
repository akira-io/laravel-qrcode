<?php

declare(strict_types=1);

use Akira\QrCode\DataTypes\LitecoinDataType;
use Akira\QrCode\Support\DataTypeMapper;
use Akira\QrCode\ValueObjects\LitecoinData;

it('generates a Litecoin QR payload', function (): void {
    $litecoinData = LitecoinData::create('ltcaddress', 1.25);
    $dataType = LitecoinDataType::fromValueObject($litecoinData);

    expect((string) $dataType)->toBe('litecoin:ltcaddress?amount=1.25');
});

it('generates a Litecoin QR payload with label and message', function (): void {
    $litecoinData = LitecoinData::create('ltcaddress', 1.25, 'Donation', 'Thanks');
    $dataType = LitecoinDataType::fromValueObject($litecoinData);

    expect((string) $dataType)->toBe('litecoin:ltcaddress?amount=1.25&label=Donation&message=Thanks');
});

it('maps dynamic Litecoin calls', function (): void {
    $payload = DataTypeMapper::createFromMethod('ltc', [
        'ltcaddress',
        1.25,
        ['label' => 'Donation'],
    ]);

    expect($payload)->toBe('litecoin:ltcaddress?amount=1.25&label=Donation');
});

it('throws an exception when Litecoin address is empty', function (): void {
    LitecoinData::create('', 1.25);
})->throws(InvalidArgumentException::class, 'Litecoin address cannot be empty');

it('throws an exception when Litecoin amount is zero or negative', function (): void {
    LitecoinData::create('ltcaddress', 0);
})->throws(InvalidArgumentException::class, 'Litecoin amount must be greater than 0');
