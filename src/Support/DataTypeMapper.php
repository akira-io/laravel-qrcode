<?php

namespace Akira\QrCode\Support;

use Akira\QrCode\DataTypes\EmailDataType;
use Akira\QrCode\DataTypes\WiFiDataType;
use Akira\QrCode\DataTypes\SMSDataType;
use Akira\QrCode\DataTypes\BitcoinDataType;
use Akira\QrCode\DataTypes\GeoDataType;
use Akira\QrCode\DataTypes\PhoneNumberDataType;
use Akira\QrCode\ValueObjects\EmailData;
use Akira\QrCode\ValueObjects\WiFiData;
use Akira\QrCode\ValueObjects\SMSData;
use Akira\QrCode\ValueObjects\BitcoinData;
use Akira\QrCode\ValueObjects\GeoLocation;
use Akira\QrCode\ValueObjects\PhoneNumber;
use BadMethodCallException;

class DataTypeMapper
{
    /**
     * @param array<int, mixed> $arguments
     */
    public static function createFromMethod(string $method, array $arguments): string
    {
        return match (strtolower($method)) {
            'email' => self::createEmail($arguments),
            'wifi' => self::createWiFi($arguments),
            'sms' => self::createSMS($arguments),
            'btc', 'bitcoin' => self::createBitcoin($arguments),
            'geo' => self::createGeo($arguments),
            'phonenumber', 'phone' => self::createPhoneNumber($arguments),
            default => throw new BadMethodCallException("Method {$method} not found"),
        };
    }

    /**
     * @param array<int, mixed> $arguments
     */
    private static function createEmail(array $arguments): string
    {
        $emailData = EmailData::create(
            address: $arguments[0] ?? '',
            subject: $arguments[1] ?? null,
            body: $arguments[2] ?? null,
            cc: $arguments[3] ?? null,
            bcc: $arguments[4] ?? null
        );

        return EmailDataType::fromValueObject($emailData)->toString();
    }

    /**
     * @param array<int, mixed> $arguments
     */
    private static function createWiFi(array $arguments): string
    {
        if (!isset($arguments[0]) || !is_array($arguments[0])) {
            throw new \InvalidArgumentException('WiFi requires an array argument');
        }

        $data = $arguments[0];
        $wifiData = WiFiData::create(
            ssid: $data['ssid'] ?? '',
            password: $data['password'] ?? null,
            hidden: $data['hidden'] ?? false
        );

        return WiFiDataType::fromValueObject($wifiData)->toString();
    }

    /**
     * @param array<int, mixed> $arguments
     */
    private static function createSMS(array $arguments): string
    {
        $smsData = SMSData::create(
            phoneNumber: $arguments[0] ?? '',
            message: $arguments[1] ?? null
        );

        return SMSDataType::fromValueObject($smsData)->toString();
    }

    /**
     * @param array<int, mixed> $arguments
     */
    private static function createBitcoin(array $arguments): string
    {
        $options = $arguments[2] ?? [];

        $bitcoinData = BitcoinData::create(
            address: $arguments[0] ?? '',
            amount: (float)($arguments[1] ?? 0),
            label: $options['label'] ?? null,
            message: $options['message'] ?? null,
            returnAddress: $options['returnAddress'] ?? null
        );

        return BitcoinDataType::fromValueObject($bitcoinData)->toString();
    }

    /**
     * @param array<int, mixed> $arguments
     */
    private static function createGeo(array $arguments): string
    {
        $geoLocation = GeoLocation::create(
            latitude: (float)($arguments[0] ?? 0),
            longitude: (float)($arguments[1] ?? 0),
            name: $arguments[2] ?? null
        );

        return GeoDataType::fromValueObject($geoLocation)->toString();
    }

    /**
     * @param array<int, mixed> $arguments
     */
    private static function createPhoneNumber(array $arguments): string
    {
        $phoneNumber = PhoneNumber::fromString($arguments[0] ?? '');

        return PhoneNumberDataType::fromValueObject($phoneNumber)->toString();
    }
}
