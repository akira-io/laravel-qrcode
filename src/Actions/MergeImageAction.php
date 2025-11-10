<?php

namespace Akira\QrCode\Actions;

use Akira\QrCode\ValueObjects\ImageMergeConfig;

class MergeImageAction
{
    public function handle(ImageMergeConfig $config): ?string
    {
        return file_get_contents($config->getFullPath()) ?: null;
    }
}
