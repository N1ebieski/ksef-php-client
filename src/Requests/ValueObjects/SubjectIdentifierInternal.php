<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\ValueObjects;

use N1ebieski\KSEFClient\Contracts\FromInterface;
use N1ebieski\KSEFClient\Support\AbstractValueObject;
use N1ebieski\KSEFClient\Validator\Rules\String\RegexRule;
use N1ebieski\KSEFClient\Validator\Validator;

final readonly class SubjectIdentifierInternal extends AbstractValueObject implements FromInterface
{
    public string $value;

    public function __construct(string $value)
    {
        Validator::validate($value, [
            new RegexRule('/[1-9]((\\d[1-9])|([1-9]\\d))\\d{7}-\\d{5}/'),
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
