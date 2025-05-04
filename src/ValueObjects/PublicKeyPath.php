<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\ValueObjects;

use InvalidArgumentException;
use N1ebieski\KSEFClient\Contracts\ValueAwareInterface;
use N1ebieski\KSEFClient\Support\ValueObject;
use Stringable;

final readonly class PublicKeyPath extends ValueObject implements ValueAwareInterface, Stringable
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

    private function validate($value): void
    {
        if ( ! is_file($value)) {
            throw new InvalidArgumentException("File {$value} does not exist.");
        }

        $extension = strtolower(pathinfo($value, PATHINFO_EXTENSION));

        if ( ! in_array($extension, ['pem', 'der'])) {
            throw new InvalidArgumentException("File {$value} has invalid extension.");
        }
    }
}
