<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects;

use DateTimeInterface;
use DateTimeImmutable;
use N1ebieski\KSEFClient\Contracts\ValueAwareInterface;
use N1ebieski\KSEFClient\Support\AbstractValueObject;
use Stringable;

final readonly class DataWytworzeniaFa extends AbstractValueObject implements ValueAwareInterface, Stringable
{
    public DateTimeInterface $value;

    public function __construct(DateTimeInterface | string $value)
    {
        if ($value instanceof \DateTimeInterface === false) {
            $value = new DateTimeImmutable($value);
        }

        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value->format('Y-m-d\TH:i:s\Z');
    }

    public static function from(string $value): self
    {
        return new self($value);
    }
}
