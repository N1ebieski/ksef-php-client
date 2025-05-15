<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Session\ValueObjects;

use N1ebieski\KSEFClient\Contracts\ValueAwareInterface;
use N1ebieski\KSEFClient\Support\AbstractValueObject;
use SensitiveParameter;
use Stringable;

final readonly class Challenge extends AbstractValueObject implements ValueAwareInterface, Stringable
{
    public function __construct(
        #[SensitiveParameter]
        public string $value
    ) {
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
