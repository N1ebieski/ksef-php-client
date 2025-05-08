<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\Responses\ValueObjects;

use N1ebieski\KSEFClient\Contracts\ValueAwareInterface;
use N1ebieski\KSEFClient\Support\ValueObject;
use N1ebieski\KSEFClient\Validator\Rules\String\MaxRule;
use N1ebieski\KSEFClient\Validator\Rules\String\MinRule;
use N1ebieski\KSEFClient\Validator\Validator;
use Stringable;

final readonly class InvoiceNumber extends ValueObject implements ValueAwareInterface, Stringable
{
    public string $value;

    public function __construct(string $value)
    {
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
