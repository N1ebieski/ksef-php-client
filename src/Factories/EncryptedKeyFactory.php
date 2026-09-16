<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Factories;

use N1ebieski\KSEFClient\ValueObjects\EncryptionKey;
use N1ebieski\KSEFClient\ValueObjects\KsefPublicKey;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\EncryptedKey;
use phpseclib4\Crypt\PublicKeyLoader;
use phpseclib4\Crypt\RSA;
use phpseclib4\Crypt\RSA\PublicKey as RSAPublicKey;

final class EncryptedKeyFactory extends AbstractFactory
{
    public static function make(EncryptionKey $encryptionKey, KsefPublicKey $ksefPublicKey): EncryptedKey
    {
        /** @var RSAPublicKey $pub */
        $pub = PublicKeyLoader::load($ksefPublicKey->value);

        $encryptedKey = $pub
            ->withPadding(RSA::ENCRYPTION_OAEP)
            ->withHash('sha256')
            ->withMGFHash('sha256')
            ->encrypt($encryptionKey->key);

        $encryptedKey = base64_encode($encryptedKey);

        $encryptedIv = base64_encode($encryptionKey->iv);

        return new EncryptedKey($encryptedKey, $encryptedIv, $ksefPublicKey->publicKeyId);
    }
}
