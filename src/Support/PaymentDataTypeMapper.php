<?php

declare(strict_types=1);

namespace Akira\QrCode\Support;

use Akira\QrCode\DataTypes\EthereumDataType;
use Akira\QrCode\DataTypes\LitecoinDataType;
use Akira\QrCode\ValueObjects\EthereumData;
use Akira\QrCode\ValueObjects\LitecoinData;
use Illuminate\Support\Fluent;

final class PaymentDataTypeMapper
{
    /**
     * @param  array<int, mixed>  $arguments
     */
    public static function createEthereum(array $arguments): string
    {
        $args = new Fluent($arguments);
        $optionsData = $args->get(2, []);
        $options = new Fluent(is_array($optionsData) ? $optionsData : []);

        $address = $args->get(0, '');
        $value = $args->get(1);
        $chainId = $options->get('chainId');
        $label = $options->get('label');
        $message = $options->get('message');

        $ethereumData = EthereumData::create(
            address: is_string($address) ? $address : '',
            value: is_numeric($value) ? (string) $value : null,
            chainId: is_numeric($chainId) ? (int) $chainId : null,
            label: is_string($label) ? $label : null,
            message: is_string($message) ? $message : null
        );

        return (string) EthereumDataType::fromValueObject($ethereumData);
    }

    /**
     * @param  array<int, mixed>  $arguments
     */
    public static function createLitecoin(array $arguments): string
    {
        $args = new Fluent($arguments);
        $optionsData = $args->get(2, []);
        $options = new Fluent(is_array($optionsData) ? $optionsData : []);

        $address = $args->get(0, '');
        $amount = $args->get(1, 0.0);
        $label = $options->get('label');
        $message = $options->get('message');

        $litecoinData = LitecoinData::create(
            address: is_string($address) ? $address : '',
            amount: is_numeric($amount) ? (float) $amount : 0.0,
            label: is_string($label) ? $label : null,
            message: is_string($message) ? $message : null
        );

        return (string) LitecoinDataType::fromValueObject($litecoinData);
    }
}
