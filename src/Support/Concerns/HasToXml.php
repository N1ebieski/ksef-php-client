<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support\Concerns;

use N1ebieski\KSEFClient\Contracts\XmlSerializableInterface;

/**
 * @mixin XmlSerializableInterface
 */
trait HasToXml
{
    public function toXml(): string
    {
        return $this->toDom()->saveXML() ?: throw new \RuntimeException('Unable to serialize to XML');
    }
}
