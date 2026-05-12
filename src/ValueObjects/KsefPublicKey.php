<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\ValueObjects;

use N1ebieski\KSEFClient\Contracts\ValueAwareInterface;
use N1ebieski\KSEFClient\Support\AbstractValueObject;
use N1ebieski\KSEFClient\ValueObjects\Requests\Security\PublicKeyCertificates\PublicKeyId;
use Stringable;

final class KsefPublicKey extends AbstractValueObject implements ValueAwareInterface, Stringable
{
    public function __construct(
        public readonly string $value,
        public readonly ?PublicKeyId $publicKeyId = null
    ) {
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public static function from(string $value, ?PublicKeyId $publicKeyId = null): self
    {
        return new self($value, $publicKeyId);
    }
}
