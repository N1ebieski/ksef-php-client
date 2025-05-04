<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\DTOs\ValueObjects;

use N1ebieski\KSEFClient\Contracts\ValueAwareInterface;
use N1ebieski\KSEFClient\Support\ValueObject;
use Stringable;

final readonly class AdresL2 extends ValueObject implements ValueAwareInterface, Stringable
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
        $length = mb_strlen($value);

        if ($length < 1 || $length > 512) {
            throw new \InvalidArgumentException('Invalid address length.');
        }
    }
}
