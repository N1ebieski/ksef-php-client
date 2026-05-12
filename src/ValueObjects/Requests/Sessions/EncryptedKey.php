<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\ValueObjects\Requests\Sessions;

use N1ebieski\KSEFClient\Contracts\ArrayableInterface;
use N1ebieski\KSEFClient\Contracts\BodyInterface;
use N1ebieski\KSEFClient\Support\AbstractValueObject;
use N1ebieski\KSEFClient\Support\Concerns\HasToArray;
use N1ebieski\KSEFClient\ValueObjects\Requests\Security\PublicKeyCertificates\PublicKeyId;

final class EncryptedKey extends AbstractValueObject implements BodyInterface, ArrayableInterface
{
    use HasToArray;

    public function __construct(
        public readonly string $key,
        public readonly string $iv,
        public readonly ?PublicKeyId $publicKeyId = null
    ) {
    }

    public static function from(string $key, string $iv, ?PublicKeyId $publicKeyId = null): self
    {
        return new self($key, $iv, $publicKeyId);
    }

    public function toBody(): array
    {
        return array_merge([
            'encryptedSymmetricKey' => $this->key,
            'initializationVector' => $this->iv
        ], $this->publicKeyId instanceof PublicKeyId ? [
            'publicKeyId' => $this->publicKeyId->value
        ] : []);
    }
}
