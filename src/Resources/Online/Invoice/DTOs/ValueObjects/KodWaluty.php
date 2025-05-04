<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\DTOs\ValueObjects;

use InvalidArgumentException;
use N1ebieski\KSEFClient\Contracts\ValueAwareInterface;
use N1ebieski\KSEFClient\Support\ValueObject;
use Stringable;

final readonly class KodWaluty extends ValueObject implements ValueAwareInterface, Stringable
{
    public string $value;

    public function __construct(string $value)
    {
        $this->validate($value);

        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public static function from(string $value): self
    {
        return new self($value);
    }

    public function validate($value): void
    {
        try {
            new \Alcohol\ISO4217()->getByAlpha3($value);
        } catch (\DomainException) {
            throw new InvalidArgumentException("Invalid currency code format: {$this->value} given.");
        } catch (\OutOfBoundsException) {
            throw new InvalidArgumentException("Currency code: {$this->value} does not exist.");
        }
    }
}
