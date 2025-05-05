<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\ValueObjects;

use N1ebieski\KSEFClient\Contracts\ValueAwareInterface;
use N1ebieski\KSEFClient\Support\ValueObject;
use N1ebieski\KSEFClient\Validator\Rules\UrlRule;
use N1ebieski\KSEFClient\Validator\Validator;
use Stringable;

final readonly class Url extends ValueObject implements ValueAwareInterface, Stringable
{
    public string $value;

    public function __construct(string $value)
    {
        Validator::validate($value, [
            new UrlRule(),
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

    public function withSlashAtEnd(): self
    {
        return str_ends_with($this->value, '/') ? $this : new self($this->value . '/');
    }
}
