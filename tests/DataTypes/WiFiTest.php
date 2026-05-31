<?php

declare(strict_types=1);

use Akira\QrCode\DataTypes\WiFiDataType;
use Akira\QrCode\ValueObjects\WiFiData;

it('should generate a valid WiFi QR code with just the SSID', function (): void {
    $wifiData = WiFiData::create('SSID');
    $dataType = WiFiDataType::fromValueObject($wifiData);

    expect((string) $dataType)->toBe('WIFI:T:nopass;S:SSID;;');
});

it('should generate a valid WiFi QR code with SSID and password', function (): void {
    $wifiData = WiFiData::create('SSID', 'password');
    $dataType = WiFiDataType::fromValueObject($wifiData);

    expect((string) $dataType)->toBe('WIFI:T:WPA;S:SSID;P:password;;');
});

it('should generate a valid WiFi QR code for a hidden SSID', function (): void {
    $wifiData = WiFiData::create('SSID', null, true);
    $dataType = WiFiDataType::fromValueObject($wifiData);

    expect((string) $dataType)->toBe('WIFI:T:nopass;S:SSID;H:true;;');
});

it('should generate a valid WiFi QR code for a hidden SSID and password', function (): void {
    $wifiData = WiFiData::create('SSID', 'password', true);
    $dataType = WiFiDataType::fromValueObject($wifiData);

    expect((string) $dataType)->toBe('WIFI:T:WPA;S:SSID;P:password;H:true;;');
});

it('should generate a valid WiFi QR code with WEP encryption', function (): void {
    $wifiData = WiFiData::create('SSID', 'password', encryption: 'WEP');
    $dataType = WiFiDataType::fromValueObject($wifiData);

    expect((string) $dataType)->toBe('WIFI:T:WEP;S:SSID;P:password;;');
});

it('should escape reserved WiFi characters', function (): void {
    $wifiData = WiFiData::create('Work;WiFi:Guest', 'pass,word\\value');
    $dataType = WiFiDataType::fromValueObject($wifiData);

    expect((string) $dataType)->toBe('WIFI:T:WPA;S:Work\;WiFi\:Guest;P:pass\,word\\\\value;;');
});

it('throws an exception when SSID is missing', function (): void {
    WiFiData::create('');
})->throws(InvalidArgumentException::class, 'SSID cannot be empty');

it('throws an exception when encryption is invalid', function (): void {
    WiFiData::create('SSID', 'password', encryption: 'INVALID');
})->throws(InvalidArgumentException::class, 'Encryption type must be WPA, WEP, or nopass');
