<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts;

interface XmlDeserializableInterface extends XmlNormalizableInterface
{
    public static function fromXml(string $xml): self;
}
