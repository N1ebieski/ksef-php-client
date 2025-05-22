<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects;

use N1ebieski\KSEFClient\Contracts\ValueAwareInterface;
use N1ebieski\KSEFClient\Support\AbstractValueObject;
use N1ebieski\KSEFClient\Validator\Rules\String\MaxRule;
use N1ebieski\KSEFClient\Validator\Rules\String\MinRule;
use N1ebieski\KSEFClient\Validator\Validator;
use Stringable;

final readonly class PKOB extends AbstractValueObject implements ValueAwareInterface, Stringable
{
    public string $value;

    public function __construct(string $value)
    {
        Validator::validate($value, [
            new MinRule(1),
            new MaxRule(50),
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
