<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\DTOs\ValueObjects;

use N1ebieski\KSEFClient\Contracts\ValueAwareInterface;
use N1ebieski\KSEFClient\Support\ValueObject;
use N1ebieski\KSEFClient\Validator\Rules\CountryRule;
use N1ebieski\KSEFClient\Validator\Validator;
use Stringable;

final readonly class KodKraju extends ValueObject implements ValueAwareInterface, Stringable
{
    public string $value;

    public function __construct(string $value)
    {
        Validator::validate($value, [
            new CountryRule(),
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
