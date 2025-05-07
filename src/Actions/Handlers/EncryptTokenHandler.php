<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Actions\Handlers;

use RuntimeException;
use N1ebieski\KSEFClient\Actions\EncryptTokenAction;
use N1ebieski\KSEFClient\Actions\Handler;
use N1ebieski\KSEFClient\Resources\Online\Session\Requests\ValueObjects\EncryptedToken;

final readonly class EncryptTokenHandler extends Handler
{
    public function handle(EncryptTokenAction $action): EncryptedToken
    {
        $timestampAsMiliseconds = $action->timestamp->getTimestamp() * 1000;

        $data = "{$action->apiToken->value}|{$timestampAsMiliseconds}";

        $publicKey = file_get_contents($action->publicKeyPath->value);

        if ($publicKey === false) {
            throw new RuntimeException("Unable to read public key from the file: {$action->publicKeyPath->value}");
        }

        $encryption = openssl_public_encrypt($data, $encryptedToken, $publicKey, OPENSSL_PKCS1_PADDING);

        if ($encryption === false) {
            throw new RuntimeException('Unable to encrypt token.');
        }

        return new EncryptedToken(base64_encode((string) $encryptedToken)); //@phpstan-ignore-line
    }
}
