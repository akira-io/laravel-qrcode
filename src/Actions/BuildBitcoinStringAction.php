<?php

declare(strict_types=1);

namespace Akira\QrCode\Actions;

use Akira\QrCode\ValueObjects\BitcoinData;

final class BuildBitcoinStringAction
{
    private const string PREFIX = 'bitcoin:';

    public function handle(BitcoinData $data): string
    {
        $params = $data->toArray();

        if ($params === []) {
            return self::PREFIX.$data->address;
        }

        return self::PREFIX.$data->address.'?'.http_build_query($params);
    }
}
