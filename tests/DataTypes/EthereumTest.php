<?php

declare(strict_types=1);

use Akira\QrCode\DataTypes\EthereumDataType;
use Akira\QrCode\Support\DataTypeMapper;
use Akira\QrCode\ValueObjects\EthereumData;

it('generates an Ethereum QR payload', function (): void {
    $ethereumData = EthereumData::create('0x0000000000000000000000000000000000000001');
    $dataType = EthereumDataType::fromValueObject($ethereumData);

    expect((string) $dataType)->toBe('ethereum:0x0000000000000000000000000000000000000001');
});

it('generates an Ethereum QR payload with value and chain ID', function (): void {
    $ethereumData = EthereumData::create(
        address: '0x0000000000000000000000000000000000000001',
        value: '1000000000000000000',
        chainId: 1,
        label: 'Donation',
        message: 'Thanks'
    );

    $dataType = EthereumDataType::fromValueObject($ethereumData);

    expect((string) $dataType)->toBe('ethereum:0x0000000000000000000000000000000000000001@1?value=1000000000000000000&label=Donation&message=Thanks');
});

it('maps dynamic Ethereum calls', function (): void {
    $payload = DataTypeMapper::createFromMethod('eth', [
        '0x0000000000000000000000000000000000000001',
        100,
        ['chainId' => 11155111],
    ]);

    expect($payload)->toBe('ethereum:0x0000000000000000000000000000000000000001@11155111?value=100');
});

it('throws an exception when Ethereum address is invalid', function (): void {
    EthereumData::create('invalid');
})->throws(InvalidArgumentException::class, 'Invalid Ethereum address');

it('throws an exception when Ethereum value is zero or negative', function (): void {
    EthereumData::create('0x0000000000000000000000000000000000000001', '0');
})->throws(InvalidArgumentException::class, 'Ethereum value must be greater than 0');
