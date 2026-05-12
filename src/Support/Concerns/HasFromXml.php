<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support\Concerns;

use CuyZ\Valinor\Cache\Cache;
use N1ebieski\KSEFClient\Actions\ConvertXmlToArray\ConvertXmlToArrayAction;
use N1ebieski\KSEFClient\Actions\ConvertXmlToArray\ConvertXmlToArrayHandler;
use N1ebieski\KSEFClient\Contracts\XmlDeserializableInterface;

/**
 * @mixin XmlDeserializableInterface
 */
trait HasFromXml
{
    use HasFromArray;

    public static function fromXml(string $xml, ?Cache $cache = null): self
    {
        $array = (new ConvertXmlToArrayHandler())->handle(new ConvertXmlToArrayAction($xml));

        $normalizedArray = self::normalizeXmlArray($array);

        return self::from($normalizedArray, $cache);
    }
}
