<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\DTOs\ValueObjects;

use DateTimeImmutable;
use N1ebieski\KSEFClient\Contracts\ValueAwareInterface;
use N1ebieski\KSEFClient\Support\Concerns\HasEvaluation;
use N1ebieski\KSEFClient\Support\ValueObject;
use Stringable;

/**
 * Kolejny numer faktury, nadany w ramach jednej lub więcej serii, który w sposób jednoznaczny identyfikuje fakturę
 */
final readonly class P_2 extends ValueObject implements ValueAwareInterface, Stringable
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
        $length = mb_strlen($value);

        if ($length < 1 || $length > 256) {
            throw new \InvalidArgumentException('Invalid name length.');
        }
    }
}
