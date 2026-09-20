<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Unit\Factories;

use N1ebieski\KSEFClient\Factories\EncryptedKeyFactory;
use N1ebieski\KSEFClient\ValueObjects\EncryptionKey;
use N1ebieski\KSEFClient\ValueObjects\KsefPublicKey;
use phpseclib4\Crypt\RSA;

test('ensure that factory encrypts the symmetric key with RSA-OAEP readable by the recipient', function (): void {
    $privateKey = RSA::createKey(2048);

    $encryptionKey = EncryptionKey::from(random_bytes(32), random_bytes(16));

    $encryptedKey = EncryptedKeyFactory::make(
        $encryptionKey,
        KsefPublicKey::from($privateKey->getPublicKey()->toString('PKCS8'))
    );

    $decrypted = $privateKey
        ->withPadding(RSA::ENCRYPTION_OAEP)
        ->withHash('sha256')
        ->withMGFHash('sha256')
        ->decrypt((string) base64_decode($encryptedKey->key, true));

    expect($decrypted)->toBe($encryptionKey->key);
    expect(base64_decode($encryptedKey->iv, true))->toBe($encryptionKey->iv);
});
