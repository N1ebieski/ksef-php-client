<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\ValueObjects\Requests\Auth;

use CuyZ\Valinor\Mapper\Object\Constructor;
use N1ebieski\KSEFClient\Contracts\FromInterface;
use N1ebieski\KSEFClient\Contracts\ValueAwareInterface;
use N1ebieski\KSEFClient\Support\AbstractValueObject;
use N1ebieski\KSEFClient\ValueObjects\Requests\Security\PublicKeyCertificates\PublicKeyId;
use SensitiveParameter;
use Stringable;

final class EncryptedToken extends AbstractValueObject implements FromInterface, ValueAwareInterface, Stringable
{
    #[Constructor]
    public function __construct(
        #[SensitiveParameter] public readonly string $value,
        public readonly ?PublicKeyId $publicKeyId = null
    ) {
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public static function from(#[SensitiveParameter] string $value, ?PublicKeyId $publicKeyId = null): self
    {
        return new self($value, $publicKeyId);
    }

    #[Constructor]
    public static function fromValue(#[SensitiveParameter] string $value): self
    {
        return new self($value);
    }
}
