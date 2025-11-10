<?php

use BaconQrCode\Renderer\Eye\SimpleCircleEye;
use BaconQrCode\Renderer\Eye\SquareEye;
use BaconQrCode\Renderer\Image\EpsImageBackEnd;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\Module\DotsModule;
use BaconQrCode\Renderer\Module\RoundnessModule;
use BaconQrCode\Renderer\Module\SquareModule;
use BaconQrCode\Renderer\RendererStyle\Gradient;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use DASPRiD\Enum\Exception\IllegalArgumentException;
use Illuminate\Support\HtmlString;
use Akira\QrCode\QrCode;

test('chaining is working', function () {
    expect((app(QrCode::class))->size(100))->toBeInstanceOf(QrCode::class);
    expect((app(QrCode::class))->format('png'))->toBeInstanceOf(QrCode::class);
    expect((app(QrCode::class))->color(100, 100, 100))->toBeInstanceOf(QrCode::class);
    expect((app(QrCode::class))->backgroundColor(100, 100, 100))->toBeInstanceOf(QrCode::class);
    expect((app(QrCode::class))->eyeColor(0, 100, 100, 100))->toBeInstanceOf(QrCode::class);
    expect((app(QrCode::class))->gradient(100, 100, 100, 100, 100, 100, 'vertical'))->toBeInstanceOf(QrCode::class);
    expect((app(QrCode::class))->eye('square'))->toBeInstanceOf(QrCode::class);
    expect((app(QrCode::class))->style('square'))->toBeInstanceOf(QrCode::class);
    expect((app(QrCode::class))->encoding('utf-8'))->toBeInstanceOf(QrCode::class);
    expect((app(QrCode::class))->errorCorrection('L'))->toBeInstanceOf(QrCode::class);
    expect((app(QrCode::class))->margin(10))->toBeInstanceOf(QrCode::class);
});

test('size is passed to renderer', function () {
    $qrCode = (app(QrCode::class))->size(200);

    expect($qrCode->getRendererStyle()->getSize())->toBe(200);
});

test('style format is passed to renderer', function () {
    $qrCode = (app(QrCode::class))->format('png');
    expect($qrCode->getFormatter())->toBeInstanceOf(ImagickImageBackEnd::class);

    $qrCode = (app(QrCode::class))->format('svg');
    expect($qrCode->getFormatter())->toBeInstanceOf(SvgImageBackEnd::class);

    $qrCode = (app(QrCode::class))->format('eps');
    expect($qrCode->getFormatter())->toBeInstanceOf(EpsImageBackEnd::class);
});

it('throws exception if format is not supported', function () {
    (app(QrCode::class))->format('jpg');
})->throws(InvalidArgumentException::class);

test('color is passed to renderer', function () {
    $qrCode = (app(QrCode::class))->color(100, 150, 200);
    expect($qrCode->getFill()->getForegroundColor()->toRgb()->getRed())->toBe(100);
    expect($qrCode->getFill()->getForegroundColor()->toRgb()->getGreen())->toBe(150);
    expect($qrCode->getFill()->getForegroundColor()->toRgb()->getBlue())->toBe(200);

    $qrCode = (app(QrCode::class))->backgroundColor(50, 75, 100);
    expect($qrCode->getFill()->getBackgroundColor()->toRgb()->getRed())->toBe(50);
    expect($qrCode->getFill()->getBackgroundColor()->toRgb()->getGreen())->toBe(75);
    expect($qrCode->getFill()->getBackgroundColor()->toRgb()->getBlue())->toBe(100);

    $qrCode = (app(QrCode::class))->color(100, 150, 200, 100);
    expect($qrCode->getFill()->getForegroundColor()->getAlpha())->toBe(100);

    $qrCode = (app(QrCode::class))->backgroundColor(50, 75, 100, 75);
    expect($qrCode->getFill()->getBackgroundColor()->getAlpha())->toBe(75);
});

test('eye color is passed to renderer', function () {
    $qrCode = (app(QrCode::class))->eyeColor(0, 0, 10, 50, 1, 8, 18);
    $qrCode->eyeColor(1, 100, 20, 60, 2, 10, 20);
    $qrCode->eyeColor(2, 200, 30, 70, 3, 12, 22);

    expect($qrCode->getFill()->getTopLeftEyeFill()->getExternalColor()->getRed())->toBe(0);
    expect($qrCode->getFill()->getTopRightEyeFill()->getExternalColor()->getRed())->toBe(100);
    expect($qrCode->getFill()->getBottomLeftEyeFill()->getExternalColor()->getRed())->toBe(200);
    expect($qrCode->getFill()->getTopLeftEyeFill()->getExternalColor()->getGreen())->toBe(10);
    expect($qrCode->getFill()->getTopRightEyeFill()->getExternalColor()->getGreen())->toBe(20);
    expect($qrCode->getFill()->getBottomLeftEyeFill()->getExternalColor()->getGreen())->toBe(30);
    expect($qrCode->getFill()->getTopLeftEyeFill()->getExternalColor()->getBlue())->toBe(50);
    expect($qrCode->getFill()->getTopRightEyeFill()->getExternalColor()->getBlue())->toBe(60);
    expect($qrCode->getFill()->getBottomLeftEyeFill()->getExternalColor()->getBlue())->toBe(70);

    expect($qrCode->getFill()->getTopLeftEyeFill()->getInternalColor()->getRed())->toBe(1);
    expect($qrCode->getFill()->getTopRightEyeFill()->getInternalColor()->getRed())->toBe(2);
    expect($qrCode->getFill()->getBottomLeftEyeFill()->getInternalColor()->getRed())->toBe(3);
    expect($qrCode->getFill()->getTopLeftEyeFill()->getInternalColor()->getGreen())->toBe(8);
    expect($qrCode->getFill()->getTopRightEyeFill()->getInternalColor()->getGreen())->toBe(10);
    expect($qrCode->getFill()->getBottomLeftEyeFill()->getInternalColor()->getGreen())->toBe(12);
    expect($qrCode->getFill()->getTopLeftEyeFill()->getInternalColor()->getBlue())->toBe(18);
    expect($qrCode->getFill()->getTopRightEyeFill()->getInternalColor()->getBlue())->toBe(20);
    expect($qrCode->getFill()->getBottomLeftEyeFill()->getInternalColor()->getBlue())->toBe(22);
});

it('throws exception if eye color greater than 2', function () {
    (app(QrCode::class))->eyeColor(3, 0, 0, 0, 255, 255, 255);
})->throws(InvalidArgumentException::class);

it('throws exception if eye color less than 0', function () {
    (app(QrCode::class))->eyeColor(-1, 0, 0, 0, 255, 255, 255);
})->throws(InvalidArgumentException::class);

test('gradient is passed to renderer', function () {
    $qrCode = (app(QrCode::class))->gradient(100, 150, 200, 50, 75, 100, 'vertical');
    expect($qrCode->getFill()->getForegroundGradient())->toBeInstanceOf(Gradient::class);
});

it('throws exception if gradient type is not supported', function () {
    (app(QrCode::class))->gradient(100, 150, 200, 50, 75, 100, 'foo');
})->throws(IllegalArgumentException::class);

test('eye style is passed to renderer', function () {
    $qrCode = (app(QrCode::class))->eye('circle');
    expect($qrCode->getEye())->toBeInstanceOf(SimpleCircleEye::class);

    $qrCode = (app(QrCode::class))->eye('square');
    expect($qrCode->getEye())->toBeInstanceOf(SquareEye::class);
});

it('throws exception if eye style is not supported', function () {
    (app(QrCode::class))->eye('dot');
})->throws(InvalidArgumentException::class);

test('module style is passed to renderer', function () {
    $qrCode = (app(QrCode::class))->style('dot');
    expect($qrCode->getModule())->toBeInstanceOf(DotsModule::class);

    $qrCode = (app(QrCode::class))->style('square');
    expect($qrCode->getModule())->toBeInstanceOf(SquareModule::class);

    $qrCode = (app(QrCode::class))->style('round');
    expect($qrCode->getModule())->toBeInstanceOf(RoundnessModule::class);
});

it('throws exception if module style is not supported', function () {
    (app(QrCode::class))->style('triangle');
})->throws(InvalidArgumentException::class);

it('throws exception if roundness module with negative roundness is set', function () {
    (app(QrCode::class))->style('round', -.5);
})->throws(InvalidArgumentException::class);

it('throws exception if roundness module with more than 1 roundness is set', function () {
    (app(QrCode::class))->style('round', 1.1);
})->throws(InvalidArgumentException::class);

it('throws exception if roundness module with 1 roundness is set', function () {
    (app(QrCode::class))->style('round', 1);
})->throws(InvalidArgumentException::class);

it('throws exception if dot module with negative roundness is set', function () {
    (app(QrCode::class))->style('dot', -.5);
})->throws(InvalidArgumentException::class);

it('throws exception if dot module with more than 1 roundness is set', function () {
    (app(QrCode::class))->style('dot', 1.1);
})->throws(InvalidArgumentException::class);

it('throws exception if dot module with 1 roundness is set', function () {
    (app(QrCode::class))->style('dot', 1);
})->throws(InvalidArgumentException::class);

test('get renderer return a renderer instance', function () {
    $qrCode = app(QrCode::class);
    expect($qrCode->getRendererStyle())->not->toBeNull()->toBeInstanceOf(RendererStyle::class);
});

it('throws exception if data type is not supported', function () {
    (app(QrCode::class))->notReal('fooBar');
})->throws(BadMethodCallException::class);

it('return html string', function () {
    $qrCode = app(QrCode::class);
    expect($qrCode->generate('This is a test'))->toBeInstanceOf(HtmlString::class);
});
