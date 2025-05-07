<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects;

use N1ebieski\KSEFClient\Contracts\ValueAwareInterface;
use N1ebieski\KSEFClient\Support\ValueObject;
use N1ebieski\KSEFClient\Validator\Rules\Number\DecimalRule;
use N1ebieski\KSEFClient\Validator\Rules\Number\MaxDigitsRule;
use N1ebieski\KSEFClient\Validator\Rules\String\RegexRule;
use N1ebieski\KSEFClient\Validator\Validator;
use Stringable;

final readonly class P_13_1 extends ValueObject implements ValueAwareInterface, Stringable
{
    public float $value;

    public function __construct(float $value)
    {
        Validator::validate((string) $value, [
            new RegexRule('/-?([1-9]\d{0,15}|0)(\.\d{1,2})?/'),
            new DecimalRule(0, 2),
            new MaxDigitsRule(18),
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
