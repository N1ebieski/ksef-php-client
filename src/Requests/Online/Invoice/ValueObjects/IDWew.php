<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects;

use N1ebieski\KSEFClient\Contracts\ValueAwareInterface;
use N1ebieski\KSEFClient\Support\AbstractValueObject;
use N1ebieski\KSEFClient\Validator\Rules\String\MaxRule;
use N1ebieski\KSEFClient\Validator\Rules\String\MinRule;
use N1ebieski\KSEFClient\Validator\Rules\String\RegexRule;
use N1ebieski\KSEFClient\Validator\Validator;
use Stringable;

final readonly class IDWew extends AbstractValueObject implements ValueAwareInterface, Stringable
{
    public string $value;

    public function __construct(string $value)
    {
        Validator::validate($value, [
            new MinRule(1),
            new MaxRule(20),
            new RegexRule('/[1-9]((\d[1-9])|([1-9]\d))\d{7}-\d{5}/'),
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
