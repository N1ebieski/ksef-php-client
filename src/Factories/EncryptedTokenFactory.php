<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Factories;

use DateTimeInterface;
use N1ebieski\KSEFClient\ValueObjects\KsefPublicKey;
use N1ebieski\KSEFClient\ValueObjects\KsefToken;
use N1ebieski\KSEFClient\ValueObjects\Requests\Auth\EncryptedToken;
use phpseclib4\Crypt\PublicKeyLoader;
use phpseclib4\Crypt\RSA;
use phpseclib4\Crypt\RSA\PublicKey as RSAPublicKey;

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

        $encryptedToken = base64_encode($encryptedToken);

        return new EncryptedToken($encryptedToken, $ksefPublicKey->publicKeyId);
    }
}
