<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\DTOs\ValueObjects;

use N1ebieski\KSEFClient\Contracts\ValueAwareInterface;
use N1ebieski\KSEFClient\Support\ValueObject;
use N1ebieski\KSEFClient\Validator\Rules\MaxRule;
use N1ebieski\KSEFClient\Validator\Rules\MinRule;
use N1ebieski\KSEFClient\Validator\Validator;
use Stringable;

/**
 * Kolejny numer faktury, nadany w ramach jednej lub więcej serii, który w sposób jednoznaczny identyfikuje fakturę
 */
final readonly class P_2 extends ValueObject implements ValueAwareInterface, Stringable
{
    public string $value;

    public function __construct(string $value)
    {
        Validator::validate($value, [
            new MinRule(1),
            new MaxRule(256),
        ]);

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
}
