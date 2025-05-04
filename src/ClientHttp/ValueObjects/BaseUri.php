<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\ClientHttp\ValueObjects;

use N1ebieski\KSEFClient\Contracts\ValueAwareInterface;
use N1ebieski\KSEFClient\Support\ValueObject;
use N1ebieski\KSEFClient\ValueObjects\Url;
use Stringable;

final readonly class BaseUri extends ValueObject implements ValueAwareInterface, Stringable
{
    public Url $value;

    public function __construct(Url | string $value)
    {
        /** @var Url $value */
        $value = $this->evaluate($value, Url::class);

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
