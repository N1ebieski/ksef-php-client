<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Common\ValueObjects;

use InvalidArgumentException;
use N1ebieski\KSEFClient\Contracts\FromInterface;
use N1ebieski\KSEFClient\Contracts\XmlSerializableInterface;
use N1ebieski\KSEFClient\Support\AbstractValueObject;
use Stringable;

final readonly class Upo extends AbstractValueObject implements Stringable, FromInterface, XmlSerializableInterface
{
    public function __construct(public string $value)
    {
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public static function from(string $value): self
    {
        return new self($value);
    }

    public function toXml(): string
    {
        return base64_decode($this->value) ?: throw new InvalidArgumentException('Cannot decode UPO.');
    }
}
