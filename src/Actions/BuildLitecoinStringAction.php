<?php

declare(strict_types=1);

namespace Akira\QrCode\Actions;

use Akira\QrCode\ValueObjects\LitecoinData;

final class BuildLitecoinStringAction
{
    private const string PREFIX = 'litecoin:';

    public function handle(LitecoinData $data): string
    {
        $params = $data->toArray();

        return self::PREFIX.$data->address.'?'.http_build_query($params);
    }
}
