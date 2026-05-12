<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support\Concerns;

use N1ebieski\KSEFClient\Actions\ConvertXmlToArray\ConvertXmlToArrayAction;
use N1ebieski\KSEFClient\Actions\ConvertXmlToArray\ConvertXmlToArrayHandler;
use N1ebieski\KSEFClient\Contracts\XmlDeserializableInterface;

/**
 * @mixin XmlDeserializableInterface
 */
trait HasFromXml
{
    public static function fromXml(string $xml): self
    {
        $array = (new ConvertXmlToArrayHandler())->handle(new ConvertXmlToArrayAction($xml));

        return self::fromXmlArray($array);
    }
}
