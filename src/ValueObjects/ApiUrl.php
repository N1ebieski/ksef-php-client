<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\ValueObjects;

use N1ebieski\KSEFClient\Contracts\ValueAwareInterface;
use N1ebieski\KSEFClient\Support\AbstractValueObject;
use Stringable;

final readonly class ApiUrl extends AbstractValueObject implements ValueAwareInterface, Stringable
{
    public Url $value;

    public function __construct(Url | string $value)
    {
        if ($value instanceof Url == false) {
            $value = Url::from($value);
        }

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
