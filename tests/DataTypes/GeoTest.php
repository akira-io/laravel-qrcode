<?php

declare(strict_types=1);

use Akira\QrCode\DataTypes\GeoDataType;
use Akira\QrCode\ValueObjects\GeoLocation;

it('should generate a valid geo QR code with name', function (): void {
    $location = GeoLocation::create(40.7128, -74.0060, 'New York');
    $dataType = GeoDataType::fromValueObject($location);

    expect((string) $dataType)->toBe('geo:40.7128,-74.006?name=New+York');
});

it('should generate a valid geo QR code without name', function (): void {
    $location = GeoLocation::create(40.7128, -74.0060);
    $dataType = GeoDataType::fromValueObject($location);

    expect((string) $dataType)->toBe('geo:40.7128,-74.006');
});

it('throws an exception when latitude is out of range (too low)', function (): void {
    GeoLocation::create(-91, 0);
})->throws(InvalidArgumentException::class, 'Latitude must be between -90 and 90');

it('throws an exception when latitude is out of range (too high)', function (): void {
    GeoLocation::create(91, 0);
})->throws(InvalidArgumentException::class, 'Latitude must be between -90 and 90');

it('throws an exception when longitude is out of range (too low)', function (): void {
    GeoLocation::create(0, -181);
})->throws(InvalidArgumentException::class, 'Longitude must be between -180 and 180');

it('throws an exception when longitude is out of range (too high)', function (): void {
    GeoLocation::create(0, 181);
})->throws(InvalidArgumentException::class, 'Longitude must be between -180 and 180');
