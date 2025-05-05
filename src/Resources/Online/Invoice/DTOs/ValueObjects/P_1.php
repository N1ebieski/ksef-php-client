<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\DTOs\ValueObjects;

use DateTimeImmutable;
use N1ebieski\KSEFClient\Contracts\ValueAwareInterface;
use N1ebieski\KSEFClient\Support\Evaluation\Evaluation;
use N1ebieski\KSEFClient\Support\Evaluation\ValueObjects\ObjectNamespace;
use N1ebieski\KSEFClient\Support\ValueObject;
use Stringable;

/**
 * Data wystawienia, z zastrzeżeniem art. 106na ust. 1 ustawy
 */
final readonly class P_1 extends ValueObject implements ValueAwareInterface, Stringable
{
    public DateTimeImmutable $value;

    public function __construct(DateTimeImmutable | string $value)
    {
        $this->value = Evaluation::evaluate($value, ObjectNamespace::from(DateTimeImmutable::class));
    }

    public function __toString(): string
    {
        return $this->value->format('Y-m-d');
    }

    public static function from(string $value): self
    {
        return new self($value);
    }
}
