<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Factories;

use DateTimeInterface;
use N1ebieski\KSEFClient\Requests\Online\Session\ValueObjects\EncryptedToken;
use N1ebieski\KSEFClient\ValueObjects\ApiToken;
use N1ebieski\KSEFClient\ValueObjects\KSEFPublicKeyPath;
use RuntimeException;
use SensitiveParameter;

final readonly class EncryptedTokenFactory extends AbstractFactory
{
    public static function make(
        #[SensitiveParameter]
        ApiToken $apiToken,
        #[SensitiveParameter]
        DateTimeInterface $timestamp,
        KSEFPublicKeyPath $ksefPublicKeyPath,
    ): EncryptedToken {
        $timestampAsMiliseconds = $timestamp->getTimestamp() * 1000;

        $data = "{$apiToken->value}|{$timestampAsMiliseconds}";

        $publicKey = file_get_contents($ksefPublicKeyPath->value);

        if ($publicKey === false) {
            throw new RuntimeException("Unable to read public key from the file: {$ksefPublicKeyPath->value}");
        }

        $encryption = openssl_public_encrypt($data, $encryptedToken, $publicKey, OPENSSL_PKCS1_PADDING);

        if ($encryption === false) {
            throw new RuntimeException('Unable to encrypt token.');
        }

        return new EncryptedToken(base64_encode((string) $encryptedToken)); //@phpstan-ignore-line
    }
}
