<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\ValueObjects\Requests\CollectiveIdentifiers;

use N1ebieski\KSEFClient\Contracts\FromInterface;
use N1ebieski\KSEFClient\Contracts\ValueAwareInterface;
use N1ebieski\KSEFClient\Support\AbstractValueObject;
use Stringable;

final class CollectiveIdentifierNumber extends AbstractValueObject implements FromInterface, ValueAwareInterface, Stringable
{
    public function __construct(public readonly string $value)
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
}
