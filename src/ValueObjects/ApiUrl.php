<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\ValueObjects;

use N1ebieski\KSEFClient\Contracts\ValueAwareInterface;
use N1ebieski\KSEFClient\Support\Evaluation\Evaluation;
use N1ebieski\KSEFClient\Support\Evaluation\ValueObjects\ObjectNamespace;
use N1ebieski\KSEFClient\Support\ValueObject;
use Stringable;

final readonly class ApiUrl extends ValueObject implements ValueAwareInterface, Stringable
{
    public Url $value;

    public function __construct(Url | string $value)
    {
        /** @var Url $value */
        $value = Evaluation::evaluate($value, ObjectNamespace::from(Url::class));

        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value->value;
    }

    public static function from(string $value): self
    {
        return new self($value);
    }
}
