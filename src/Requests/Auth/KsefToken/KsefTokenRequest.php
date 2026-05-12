<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Auth\KsefToken;

use N1ebieski\KSEFClient\Contracts\BodyInterface;
use N1ebieski\KSEFClient\DTOs\Requests\Auth\AuthorizationPolicy;
use N1ebieski\KSEFClient\DTOs\Requests\Auth\ContextIdentifierGroup;
use N1ebieski\KSEFClient\Requests\AbstractRequest;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\ValueObjects\Requests\Auth\Challenge;
use N1ebieski\KSEFClient\ValueObjects\Requests\Auth\EncryptedToken;
use N1ebieski\KSEFClient\ValueObjects\Requests\Security\PublicKeyCertificates\PublicKeyId;

final class KsefTokenRequest extends AbstractRequest implements BodyInterface
{
    public function __construct(
        public readonly Challenge $challenge,
        public readonly ContextIdentifierGroup $contextIdentifierGroup,
        public readonly EncryptedToken $encryptedToken,
        public readonly Optional | AuthorizationPolicy $authorizationPolicy = new Optional(),
    ) {
    }

    public function toBody(): array
    {
        /** @var array<string, mixed> */
        $data = $this->toArray(only: ['challenge', 'authorizationPolicy']);

        return array_merge($data, [
            'encryptedToken' => $this->encryptedToken->value,
            'contextIdentifier' => $this->contextIdentifierGroup->toBody(),
        ], $this->encryptedToken->publicKeyId instanceof PublicKeyId ? [
            'publicKeyId' => $this->encryptedToken->publicKeyId->value,
        ] : []);
    }
}
