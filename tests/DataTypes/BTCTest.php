<?php

use Akira\QrCode\DataTypes\BitcoinDataType;
use Akira\QrCode\ValueObjects\BitcoinData;

it('should generate a valid BTC QR code', function () {
    $bitcoinData = BitcoinData::create('btcaddress', 0.0034);
    $dataType = BitcoinDataType::fromValueObject($bitcoinData);
    
    expect((string) $dataType)->toBe('bitcoin:btcaddress?amount=0.0034');
});

it('should generate a valid BTC QR code with label', function () {
    $bitcoinData = BitcoinData::create('btcaddress', 0.0034, 'label');
    $dataType = BitcoinDataType::fromValueObject($bitcoinData);
    
    expect((string) $dataType)->toBe('bitcoin:btcaddress?amount=0.0034&label=label');
});

it('should generate a valid BTC QR code with message', function () {
    $bitcoinData = BitcoinData::create('btcaddress', 0.0034, null, 'message');
    $dataType = BitcoinDataType::fromValueObject($bitcoinData);
    
    expect((string) $dataType)->toBe('bitcoin:btcaddress?amount=0.0034&message=message');
});

it('should generate a valid BTC QR code with label and message', function () {
    $bitcoinData = BitcoinData::create('btcaddress', 0.0034, 'label', 'message');
    $dataType = BitcoinDataType::fromValueObject($bitcoinData);
    
    expect((string) $dataType)->toBe('bitcoin:btcaddress?amount=0.0034&label=label&message=message');
});

it('should generate a valid BTC QR code with label and message and return address', function () {
    $bitcoinData = BitcoinData::create('btcaddress', 0.0034, 'label', 'message', 'https://www.returnaddress.com');
    $dataType = BitcoinDataType::fromValueObject($bitcoinData);
    
    expect((string) $dataType)->toBe('bitcoin:btcaddress?amount=0.0034&label=label&message=message&r=https%3A%2F%2Fwww.returnaddress.com');
});

it('throws an exception when Bitcoin address is empty', function () {
    BitcoinData::create('', 0.0034);
})->throws(InvalidArgumentException::class, 'Bitcoin address cannot be empty');

it('throws an exception when Bitcoin amount is zero or negative', function () {
    BitcoinData::create('btcaddress', 0);
})->throws(InvalidArgumentException::class, 'Bitcoin amount must be greater than 0');
