<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\DTOs\ValueObjects;

use InvalidArgumentException;
use N1ebieski\KSEFClient\Contracts\ValueAwareInterface;
use N1ebieski\KSEFClient\Support\ValueObject;
use Stringable;

final readonly class KodKraju extends ValueObject implements ValueAwareInterface, Stringable
{
    public function __construct(public string $value)
    {
        $this->validate();
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public static function from(string $value): self
    {
        return new self($value);
    }

    public function validate(): void
    {
        try {
            new \League\ISO3166\ISO3166()->alpha2($this->value);
        } catch (\League\ISO3166\Exception\DomainException) {
            throw new InvalidArgumentException("Invalid country code format: {$this->value} given.");
        } catch (\InvalidArgumentException) {
            throw new InvalidArgumentException("Country code: {$this->value} does not exist.");
        }
    }
}
