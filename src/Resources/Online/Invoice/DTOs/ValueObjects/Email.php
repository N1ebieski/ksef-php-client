<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\DTOs\ValueObjects;

use N1ebieski\KSEFClient\Contracts\ValueAwareInterface;
use N1ebieski\KSEFClient\Support\ValueObject;
use N1ebieski\KSEFClient\Validator\Rules\EmailRule;
use N1ebieski\KSEFClient\Validator\Rules\MaxRule;
use N1ebieski\KSEFClient\Validator\Rules\MinRule;
use N1ebieski\KSEFClient\Validator\Validator;
use Stringable;

final readonly class Email extends ValueObject implements ValueAwareInterface, Stringable
{
    public string $value;

    public function __construct(string $value)
    {
        Validator::validate($value, [
            new MinRule(3),
            new MaxRule(255),
            new EmailRule(),
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
