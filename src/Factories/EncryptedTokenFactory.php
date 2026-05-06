<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Factories;

use DateTimeInterface;
use N1ebieski\KSEFClient\ValueObjects\KsefPublicKey;
use N1ebieski\KSEFClient\ValueObjects\KsefToken;
use N1ebieski\KSEFClient\ValueObjects\Requests\Auth\EncryptedToken;
use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Crypt\RSA;
use phpseclib3\Crypt\RSA\PublicKey as RSAPublicKey;
use RuntimeException;

final class EncryptedTokenFactory extends AbstractFactory
{
    public static function make(
        KsefToken $ksefToken,
        DateTimeInterface | int $timestamp,
        KsefPublicKey $ksefPublicKey,
    ): EncryptedToken {
        if ($timestamp instanceof DateTimeInterface) {
            $secondsWithMicro = (float) $timestamp->format('U.u');
            $timestamp = (int) floor($secondsWithMicro * 1000);
        }

        $data = "{$ksefToken->value}|{$timestamp}";

        /** @var RSAPublicKey $pub */
        $pub = PublicKeyLoader::load($ksefPublicKey->value);

        $encryptedToken = $pub
            ->withPadding(RSA::ENCRYPTION_OAEP)
            ->withHash('sha256')
            ->withMGFHash('sha256')
            ->encrypt($data);

        if ($encryptedToken === false) {
            throw new RuntimeException('Unable to encrypt token');
        }

        /** @var string $encryptedToken */
        $encryptedToken = base64_encode((string) $encryptedToken); //@phpstan-ignore-line

        return new EncryptedToken($encryptedToken, $ksefPublicKey->publicKeyId);
    }
}
