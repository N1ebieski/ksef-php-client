<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\ValueObjects;

use InvalidArgumentException;
use N1ebieski\KSEFClient\Contracts\ValueAwareInterface;
use N1ebieski\KSEFClient\Support\ValueObject;
use Stringable;

final readonly class Url extends ValueObject implements ValueAwareInterface, Stringable
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

    public function withSlashAtEnd(): self
    {
        return str_ends_with($this->value, '/') ? $this : new self($this->value . '/');
    }

    private function validate(): void
    {
        if (filter_var($this->value, FILTER_VALIDATE_URL) === false) {
            throw new InvalidArgumentException("Invalid url format: {$this->value} given");
        }
    }
}
