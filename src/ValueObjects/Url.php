<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\ValueObjects;

use N1ebieski\KSEFClient\Support\ValueObject;
use Stringable;

final readonly class Url extends ValueObject implements Stringable
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

    public function withSlashAtEnd(): self
    {
        return ! str_ends_with($this->value, '/') ? new self($this->value . '/') : $this;
    }

    private function validate(string $value): void
    {
        if (filter_var($value, FILTER_VALIDATE_URL) === false) {
            throw new \InvalidArgumentException("Invalid url format: {$value} given");
        }
    }
}
