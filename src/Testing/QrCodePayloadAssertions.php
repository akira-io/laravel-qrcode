<?php

declare(strict_types=1);

namespace Akira\QrCode\Testing;

use PHPUnit\Framework\Assert;

final class QrCodePayloadAssertions
{
    public static function assertPayloadSame(string $expectedPayload, string $actualPayload): void
    {
        Assert::assertSame($expectedPayload, $actualPayload);
    }

    public static function assertPayloadContains(string $expectedContent, string $actualPayload): void
    {
        Assert::assertStringContainsString($expectedContent, $actualPayload);
    }

    public static function assertPayloadStartsWith(string $expectedPrefix, string $actualPayload): void
    {
        Assert::assertNotSame('', $expectedPrefix);
        Assert::assertTrue(str_starts_with($actualPayload, $expectedPrefix));
    }

    /**
     * @param  callable(mixed): mixed  $decoder
     */
    public static function assertDecodedPayload(mixed $source, callable $decoder, ?string $expectedPayload = null): void
    {
        $decodedPayload = $decoder($source);

        Assert::assertIsString($decodedPayload, 'QR decoder must return a string payload.');
        Assert::assertNotSame('', $decodedPayload, 'QR decoder returned an empty payload.');

        if ($expectedPayload === null) {
            return;
        }

        Assert::assertSame($expectedPayload, $decodedPayload);
    }
}
