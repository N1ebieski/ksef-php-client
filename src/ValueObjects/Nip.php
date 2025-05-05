<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\ValueObjects;

use InvalidArgumentException;
use N1ebieski\KSEFClient\Contracts\ValueAwareInterface;
use N1ebieski\KSEFClient\Support\ValueObject;
use N1ebieski\KSEFClient\Validator\Rules\NipRule;
use N1ebieski\KSEFClient\Validator\Validator;
use Stringable;

final readonly class Nip extends ValueObject implements ValueAwareInterface, Stringable
{
    public string $value;

    public function __construct(
        string $value,
        bool $skipValidation = false
    ) {
        if ( ! $skipValidation) {
            Validator::validate($value, [
                new NipRule(),
            ]);
        }

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
