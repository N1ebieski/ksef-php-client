<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\ValueObjects;

use N1ebieski\KSEFClient\Contracts\ValueAwareInterface;
use N1ebieski\KSEFClient\Support\AbstractValueObject;
use N1ebieski\KSEFClient\Support\Concerns\HasDocumentHash;
use Stringable;

final readonly class EncryptedDocument extends AbstractValueObject implements ValueAwareInterface, Stringable
{
    use HasDocumentHash;

    public function __construct(public string $value)
    {
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public static function from(string $value): self
    {
        return new self($value);
    }

    public function toDocument(): string
    {
        return $this->value;
    }
}
