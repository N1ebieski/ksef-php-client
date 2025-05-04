<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Actions\Handlers;

use N1ebieski\KSEFClient\Actions\Action;
use N1ebieski\KSEFClient\Actions\DTOs\EncryptTokenAction;
use N1ebieski\KSEFClient\Actions\Handler;
use N1ebieski\KSEFClient\Resources\Online\Session\ValueObjects\EncryptedToken;

final readonly class EncryptTokenHandler extends Handler
{
    public function handle(EncryptTokenAction $action): EncryptedToken
    {
        $timestampAsMiliseconds = $action->timestamp->getTimestamp() * 1000;

        $data = "{$action->apiToken->value}|{$timestampAsMiliseconds}";

        $publicKey = file_get_contents($action->publicKeyPath->value);

        openssl_public_encrypt($data, $encryptedToken, $publicKey, OPENSSL_PKCS1_PADDING);

        return new EncryptedToken(base64_encode($encryptedToken));
    }
}
