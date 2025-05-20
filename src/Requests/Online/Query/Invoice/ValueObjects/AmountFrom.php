<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Query\Invoice\ValueObjects;

use N1ebieski\KSEFClient\Contracts\ValueAwareInterface;
use N1ebieski\KSEFClient\Support\AbstractValueObject;
use Stringable;

final readonly class AmountFrom extends AbstractValueObject implements ValueAwareInterface, Stringable
{
    public function __construct(public float $value)
    {
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }

    public static function from(float $value): self
    {
        return new self($value);
    }
}
