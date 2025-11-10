<?php

namespace Akira\QrCode\Actions;

use Akira\QrCode\ValueObjects\Color;
use BaconQrCode\Renderer\Color\Alpha;
use BaconQrCode\Renderer\Color\ColorInterface;
use BaconQrCode\Renderer\Color\Rgb;

class CreateColorAction
{
    public function handle(Color $color): ColorInterface
    {
        if ($color->hasAlpha()) {
            return new Alpha((int) $color->alpha, new Rgb($color->red, $color->green, $color->blue));
        }

        return new Rgb($color->red, $color->green, $color->blue);
    }
}
