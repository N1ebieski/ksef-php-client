<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects;

use N1ebieski\KSEFClient\Contracts\ValueAwareInterface;
use N1ebieski\KSEFClient\Support\AbstractValueObject;
use N1ebieski\KSEFClient\Validator\Rules\Number\DecimalRule;
use N1ebieski\KSEFClient\Validator\Rules\Number\MaxDigitsRule;
use N1ebieski\KSEFClient\Validator\Rules\String\RegexRule;
use N1ebieski\KSEFClient\Validator\Validator;
use Stringable;

final readonly class P_9B extends AbstractValueObject implements ValueAwareInterface, Stringable
{
    public float $value;

    public function __construct(float $value)
    {
        Validator::validate((string) $value, [
            new RegexRule('/-?([1-9]\d{0,13}|0)(\.\d{1,8})?/'),
            new DecimalRule(0, 8),
            new MaxDigitsRule(22),
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
