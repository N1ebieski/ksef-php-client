<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\DTOs\ValueObjects;

use N1ebieski\KSEFClient\Contracts\ValueAwareInterface;
use N1ebieski\KSEFClient\Support\ValueObject;
use Stringable;

final readonly class Nazwa extends ValueObject implements ValueAwareInterface, Stringable
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
        $length = mb_strlen($this->value);

        if ($length < 1 || $length > 512) {
            throw new \InvalidArgumentException('Invalid name length.');
        }
    }
}
