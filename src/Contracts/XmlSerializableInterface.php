<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts;

interface XmlSerializableInterface
{
    public function toXml(): string;
}
