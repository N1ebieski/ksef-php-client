<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\ValueObjects;

use N1ebieski\KSEFClient\Contracts\FromInterface;
use N1ebieski\KSEFClient\Support\ValueObject;
use Stringable;

final readonly class ReferenceNumber extends ValueObject implements Stringable, FromInterface
{
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
}
