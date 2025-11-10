<?php

use Akira\QrCode\DataTypes\WiFiDataType;
use Akira\QrCode\ValueObjects\WiFiData;

it('should generate a valid WiFi QR code with just the SSID', function () {
    $wifiData = WiFiData::create('SSID');
    $dataType = WiFiDataType::fromValueObject($wifiData);
    
    expect((string) $dataType)->toBe('WIFI:S:SSID;');
});

it('should generate a valid WiFi QR code with SSID and password', function () {
    $wifiData = WiFiData::create('SSID', 'password');
    $dataType = WiFiDataType::fromValueObject($wifiData);
    
    expect((string) $dataType)->toBe('WIFI:T:WPA;S:SSID;P:password;');
});

it('should generate a valid WiFi QR code for a hidden SSID', function () {
    $wifiData = WiFiData::create('SSID', null, true);
    $dataType = WiFiDataType::fromValueObject($wifiData);
    
    expect((string) $dataType)->toBe('WIFI:S:SSID;H:true;');
});

it('should generate a valid WiFi QR code for a hidden SSID and password', function () {
    $wifiData = WiFiData::create('SSID', 'password', true);
    $dataType = WiFiDataType::fromValueObject($wifiData);
    
    expect((string) $dataType)->toBe('WIFI:T:WPA;S:SSID;P:password;H:true;');
});

it('throws an exception when SSID is missing', function () {
    WiFiData::create('');
})->throws(InvalidArgumentException::class, 'SSID cannot be empty');
