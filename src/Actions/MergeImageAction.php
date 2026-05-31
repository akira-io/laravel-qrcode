<?php

declare(strict_types=1);

namespace Akira\QrCode\Actions;

use Akira\QrCode\ValueObjects\ImageMergeConfig;
use RuntimeException;

final class MergeImageAction
{
    public function handle(ImageMergeConfig $config): string
    {
        $path = $config->getFullPath();

        throw_unless(is_file($path) && is_readable($path), RuntimeException::class, "Image file is not readable: {$path}");

        $contents = file_get_contents($path);

        throw_if($contents === false, RuntimeException::class, "Unable to read image file: {$path}");

        return $contents;
    }
}
