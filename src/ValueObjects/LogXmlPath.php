<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\ValueObjects;

use N1ebieski\KSEFClient\Contracts\ValueAwareInterface;
use N1ebieski\KSEFClient\Support\AbstractValueObject;
use N1ebieski\KSEFClient\Validator\Rules\Directory\ExistsRule;
use N1ebieski\KSEFClient\Validator\Validator;
use Stringable;

final readonly class LogXmlPath extends AbstractValueObject implements ValueAwareInterface, Stringable
{
    public string $value;

    public function __construct(string $value)
    {
        Validator::validate($value, [
            new ExistsRule(),
        ]);

        $this->value = $value;
    }

    public function withSlashAtEnd(): self
    {
        return str_ends_with($this->value, '/') ? $this : new self($this->value . '/');
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
