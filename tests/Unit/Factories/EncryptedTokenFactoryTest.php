<?php

declare(strict_types=1);

use N1ebieski\KSEFClient\Factories\EncryptedTokenFactory;
use N1ebieski\KSEFClient\ValueObjects\KsefPublicKey;
use N1ebieski\KSEFClient\ValueObjects\KsefToken;
use phpseclib4\Crypt\RSA;

/**
 * @return array<int, array<int, DateTimeImmutable|int|string>>
 */
dataset('timestampProvider', fn (): array => [
    [1758024225123, '1758024225123'],
    [new DateTimeImmutable('2026-09-16T10:03:45.123456+00:00'), '1789553025123'],
]);

test('ensure that factory encrypts the token with RSA-OAEP readable by the recipient', function (
    DateTimeImmutable | int $timestamp,
    string $expectedTimestamp
): void {
    $privateKey = RSA::createKey(2048);

    $ksefToken = KsefToken::from('20260916-TK-ABCDEF0123-4567890ABC-DE');

    $encryptedToken = EncryptedTokenFactory::make(
        $ksefToken,
        $timestamp,
        KsefPublicKey::from($privateKey->getPublicKey()->toString('PKCS8'))
    );

    $decrypted = $privateKey
        ->withPadding(RSA::ENCRYPTION_OAEP)
        ->withHash('sha256')
        ->withMGFHash('sha256')
        ->decrypt((string) base64_decode($encryptedToken->value, true));

    expect($decrypted)->toBe("{$ksefToken->value}|{$expectedTimestamp}");
})->with('timestampProvider');
