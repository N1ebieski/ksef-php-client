<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\ValueObjects\Requests\CollectiveIdentifiers;

use N1ebieski\KSEFClient\Contracts\ValueAwareInterface;
use N1ebieski\KSEFClient\Support\AbstractValueObject;
use Stringable;

final class Amount extends AbstractValueObject implements ValueAwareInterface, Stringable
{
    public readonly string $value;

    public function __construct(float | string $value)
    {
        $this->value = (string) $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public static function from(float | string $value): self
    {
        return new self($value);
    }
}
