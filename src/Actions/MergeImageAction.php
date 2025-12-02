<?php

declare(strict_types=1);

namespace Akira\QrCode\Actions;

use Akira\QrCode\ValueObjects\ImageMergeConfig;

final class MergeImageAction
{
    public function handle(ImageMergeConfig $config): ?string
    {
        return file_get_contents($config->getFullPath()) ?: null;
    }
}
