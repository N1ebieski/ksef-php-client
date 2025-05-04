<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\DTOs\ValueObjects;

use InvalidArgumentException;
use N1ebieski\KSEFClient\Contracts\ValueAwareInterface;
use N1ebieski\KSEFClient\Support\ValueObject;
use Stringable;

final readonly class KodKraju extends ValueObject implements ValueAwareInterface, Stringable
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
            new \League\ISO3166\ISO3166()->alpha2($value);
        } catch (\League\ISO3166\Exception\DomainException) {
            throw new InvalidArgumentException("Invalid country code format: {$value} given.");
        } catch (\InvalidArgumentException) {
            throw new InvalidArgumentException("Country code: {$value} does not exist.");
        }
    }
}
