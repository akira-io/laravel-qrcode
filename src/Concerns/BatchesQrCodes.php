<?php

declare(strict_types=1);

namespace Akira\QrCode\Concerns;

use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;

trait BatchesQrCodes
{
    /**
     * @param  iterable<int|string, string>  $texts
     * @return Collection<int|string, HtmlString|string|null>
     */
    public function batch(iterable $texts): Collection
    {
        return collect($texts)->map(fn (string $text): HtmlString|string|null => $this->generate($text));
    }

    /**
     * @param  iterable<int|string, string>  $texts
     * @return Collection<int|string, string|null>
     */
    public function batchRaw(iterable $texts): Collection
    {
        return collect($texts)->map(fn (string $text): ?string => $this->generateRaw($text));
    }
}
