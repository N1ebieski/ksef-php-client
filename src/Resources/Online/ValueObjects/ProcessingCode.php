<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\ValueObjects;

use N1ebieski\KSEFClient\Contracts\FromInterface;
use N1ebieski\KSEFClient\Support\AbstractValueObject;
use Stringable;

final readonly class ProcessingCode extends AbstractValueObject implements Stringable, FromInterface
{
    public function __construct(public int $value)
    {
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }

    public static function from(int $value): self
    {
        return new self($value);
    }
}
