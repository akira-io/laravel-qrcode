<?php

declare(strict_types=1);

use Akira\QrCode\Testing\QrCodePayloadAssertions;

it('asserts that payloads are identical', function (): void {
    QrCodePayloadAssertions::assertPayloadSame('mailto:test@example.com', 'mailto:test@example.com');
});

it('asserts that a payload contains expected content', function (): void {
    QrCodePayloadAssertions::assertPayloadContains('test@example.com', 'mailto:test@example.com');
});

it('asserts that a payload starts with an expected prefix', function (): void {
    QrCodePayloadAssertions::assertPayloadStartsWith('WIFI:', 'WIFI:S:Network;P:secret;');
});

it('asserts decoded QR payloads through an injected decoder', function (): void {
    QrCodePayloadAssertions::assertDecodedPayload(
        source: 'fake-image-content',
        decoder: fn (string $source): string => $source === 'fake-image-content' ? 'https://example.com' : '',
        expectedPayload: 'https://example.com'
    );
});
