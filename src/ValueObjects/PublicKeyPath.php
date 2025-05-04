<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\ValueObjects;

use N1ebieski\KSEFClient\Contracts\ValueAwareInterface;
use N1ebieski\KSEFClient\Support\ValueObject;
use Stringable;

final readonly class PublicKeyPath extends ValueObject implements ValueAwareInterface, Stringable
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

    private function validate(): void
    {
        if ( ! is_file($this->value)) {
            throw new \InvalidArgumentException("File {$this->value} does not exist.");
        }

        $extension = strtolower(pathinfo($this->value, PATHINFO_EXTENSION));

        if ( ! in_array($extension, ['pem', 'der'])) {
            throw new \InvalidArgumentException("File {$this->value} has invalid extension.");
        }
    }
}
