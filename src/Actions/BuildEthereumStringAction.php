<?php

declare(strict_types=1);

namespace Akira\QrCode\Actions;

use Akira\QrCode\ValueObjects\EthereumData;

final class BuildEthereumStringAction
{
    private const string PREFIX = 'ethereum:';

    public function handle(EthereumData $data): string
    {
        $uri = self::PREFIX.$data->address;

        if ($data->chainId !== null) {
            $uri .= '@'.$data->chainId;
        }

        $params = $data->toArray();

        if ($params === []) {
            return $uri;
        }

        return $uri.'?'.http_build_query($params);
    }
}
