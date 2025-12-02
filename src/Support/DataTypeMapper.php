<?php

declare(strict_types=1);

namespace Akira\QrCode\Support;

use Akira\QrCode\DataTypes\BitcoinDataType;
use Akira\QrCode\DataTypes\EmailDataType;
use Akira\QrCode\DataTypes\GeoDataType;
use Akira\QrCode\DataTypes\PhoneNumberDataType;
use Akira\QrCode\DataTypes\SMSDataType;
use Akira\QrCode\DataTypes\WiFiDataType;
use Akira\QrCode\ValueObjects\BitcoinData;
use Akira\QrCode\ValueObjects\EmailData;
use Akira\QrCode\ValueObjects\GeoLocation;
use Akira\QrCode\ValueObjects\PhoneNumber;
use Akira\QrCode\ValueObjects\SMSData;
use Akira\QrCode\ValueObjects\WiFiData;
use BadMethodCallException;
use Illuminate\Support\Fluent;
use InvalidArgumentException;

final class DataTypeMapper
{
    /**
     * @param  array<int, mixed>  $arguments
     */
    public static function createFromMethod(string $method, array $arguments): string
    {
        return match (mb_strtolower($method)) {
            'text' => self::createText($arguments),
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
     * @param  array<int, mixed>  $arguments
     */
    private static function createText(array $arguments): string
    {
        $text = $arguments[0] ?? '';

        return is_string($text) ? $text : '';
    }

    /**
     * @param  array<int, mixed>  $arguments
     */
    private static function createEmail(array $arguments): string
    {
        [$address, $subject, $body, $cc, $bcc] = array_pad($arguments, 5, null);

        $emailData = EmailData::create(
            address: self::stringOrEmpty($address),
            subject: self::stringOrNull($subject),
            body: self::stringOrNull($body),
            cc: self::stringOrNull($cc),
            bcc: self::stringOrNull($bcc),
        );

        return (string) EmailDataType::fromValueObject($emailData);
    }

    private static function stringOrEmpty(mixed $value): string
    {
        return is_string($value) ? $value : '';
    }

    private static function stringOrNull(mixed $value): ?string
    {
        return is_string($value) ? $value : null;
    }

    /**
     * @param  array<int, mixed>  $arguments
     */
    private static function createWiFi(array $arguments): string
    {
        $data = $arguments[0] ?? null;

        throw_unless(is_array($data), InvalidArgumentException::class, 'WiFi requires an array argument.');

        $ssid = $data['ssid'] ?? '';
        $password = $data['password'] ?? null;
        $hidden = $data['hidden'] ?? false;

        $wifiData = WiFiData::create(
            ssid: is_string($ssid) ? $ssid : '',
            password: is_string($password) ? $password : null,
            hidden: is_bool($hidden) && $hidden,
        );

        return (string) WiFiDataType::fromValueObject($wifiData);
    }

    /**
     * @param  array<int, mixed>  $arguments
     */
    private static function createSMS(array $arguments): string
    {
        $args = new Fluent($arguments);
        $phoneNumber = $args->get(0, '');
        $message = $args->get(1);

        $smsData = SMSData::create(
            phoneNumber: is_string($phoneNumber) ? $phoneNumber : '',
            message: is_string($message) ? $message : null
        );

        return (string) SMSDataType::fromValueObject($smsData);
    }

    /**
     * @param  array<int, mixed>  $arguments
     */
    private static function createBitcoin(array $arguments): string
    {
        $args = new Fluent($arguments);
        $optionsData = $args->get(2, []);
        $options = new Fluent(is_array($optionsData) ? $optionsData : []);

        $address = $args->get(0, '');
        $amount = $args->get(1, 0.0);
        $label = $options->get('label');
        $message = $options->get('message');
        $returnAddress = $options->get('returnAddress');

        $bitcoinData = BitcoinData::create(
            address: is_string($address) ? $address : '',
            amount: is_numeric($amount) ? (float) $amount : 0.0,
            label: is_string($label) ? $label : null,
            message: is_string($message) ? $message : null,
            returnAddress: is_string($returnAddress) ? $returnAddress : null
        );

        return (string) BitcoinDataType::fromValueObject($bitcoinData);
    }

    /**
     * @param  array<int, mixed>  $arguments
     */
    private static function createGeo(array $arguments): string
    {
        $args = new Fluent($arguments);
        $latitude = $args->get(0, 0.0);
        $longitude = $args->get(1, 0.0);
        $name = $args->get(2);

        $geoLocation = GeoLocation::create(
            latitude: is_numeric($latitude) ? (float) $latitude : 0.0,
            longitude: is_numeric($longitude) ? (float) $longitude : 0.0,
            name: is_string($name) ? $name : null
        );

        return (string) GeoDataType::fromValueObject($geoLocation);
    }

    /**
     * @param  array<int, mixed>  $arguments
     */
    private static function createPhoneNumber(array $arguments): string
    {
        $args = new Fluent($arguments);
        $phoneNumberStr = $args->get(0, '');
        $phoneNumber = PhoneNumber::fromString(is_string($phoneNumberStr) ? $phoneNumberStr : '');

        return (string) PhoneNumberDataType::fromValueObject($phoneNumber);
    }
}
