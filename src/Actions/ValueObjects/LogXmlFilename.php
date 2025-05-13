<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Actions\ValueObjects;

use N1ebieski\KSEFClient\Contracts\ValueAwareInterface;
use N1ebieski\KSEFClient\Support\ValueObject;
use N1ebieski\KSEFClient\Validator\Rules\File\ExtensionsRule;
use N1ebieski\KSEFClient\Validator\Validator;
use Stringable;

final readonly class LogXmlFilename extends ValueObject implements ValueAwareInterface, Stringable
{
    public string $value;

    public function __construct(string $value)
    {
        Validator::validate($value, [
            new ExtensionsRule(['xml']),
        ]);

        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function withoutSlashAtStart(): self
    {
        return str_starts_with($this->value, '/') ? new self(substr($this->value, 0, -1)) : $this;
    }

    public static function from(string $value): self
    {
        return new self($value);
    }
}
