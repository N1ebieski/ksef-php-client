<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects;

use N1ebieski\KSEFClient\Contracts\ValueAwareInterface;
use N1ebieski\KSEFClient\Support\AbstractValueObject;
use N1ebieski\KSEFClient\Validator\Rules\Number\DecimalRule;
use N1ebieski\KSEFClient\Validator\Rules\Number\MaxDigitsRule;
use N1ebieski\KSEFClient\Validator\Rules\Number\MaxRule;
use N1ebieski\KSEFClient\Validator\Rules\Number\MinRule;
use N1ebieski\KSEFClient\Validator\Validator;
use Stringable;

final readonly class Udzial extends AbstractValueObject implements ValueAwareInterface, Stringable
{
    public float $value;

    public function __construct(float $value)
    {
        Validator::validate((string) $value, [
            new DecimalRule(0, 6),
            new MaxDigitsRule(9),
        ]);

        Validator::validate((float) $value, [
            new MinRule(0),
            new MaxRule(100),
        ]);

        $this->value = $value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }

    public static function from(float $value): self
    {
        return new self($value);
    }
}
