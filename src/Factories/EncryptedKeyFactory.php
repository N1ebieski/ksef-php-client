<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Factories;

use N1ebieski\KSEFClient\Requests\Online\Session\ValueObjects\EncryptedKey;
use N1ebieski\KSEFClient\ValueObjects\EncryptionKey;
use N1ebieski\KSEFClient\ValueObjects\KSEFPublicKeyPath;
use RuntimeException;

final readonly class EncryptedKeyFactory extends AbstractFactory
{
    public static function make(EncryptionKey $encryptionKey, KSEFPublicKeyPath $ksefPublicKeyPath): EncryptedKey
    {
        $ksefPublicKey = file_get_contents($ksefPublicKeyPath->value);

        if ($ksefPublicKey === false) {
            throw new RuntimeException('Unable to read KSEF public key');
        }

        $encryptKey = openssl_public_encrypt($encryptionKey->key, $encryptedKey, $ksefPublicKey, OPENSSL_PKCS1_PADDING);

        if ($encryptKey === false) {
            throw new RuntimeException('Unable to encrypt key');
        }

        /** @var string $encryptedKey */
        $encryptedKey = base64_encode($encryptedKey);

        /** @var string $encryptedIv */
        $encryptedIv = base64_encode($encryptionKey->iv);

        return new EncryptedKey($encryptedKey, $encryptedIv);
    }
}
