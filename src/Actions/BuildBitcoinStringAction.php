<?php

namespace Akira\QrCode\Actions;

use Akira\QrCode\ValueObjects\BitcoinData;

class BuildBitcoinStringAction
{
    private const PREFIX = 'bitcoin:';

    public function handle(BitcoinData $data): string
    {
        $params = $data->toArray();

        return self::PREFIX.$data->address.'?'.http_build_query($params);
    }
}
