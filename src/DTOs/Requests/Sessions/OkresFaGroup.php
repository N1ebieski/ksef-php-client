<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class OkresFaGroup extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    /**
     * @param OkresFa $okresFa Okres, którego dotyczy faktura w przypadkach, o których mowa w art. 19a ust. 3 zdanie pierwsze i ust. 4 oraz ust. 5 pkt 4 ustawy
     */
    public function __construct(
        public readonly OkresFa $okresFa,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $okresFaGroup = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'OkresFaGroup');
        $dom->appendChild($okresFaGroup);

        $okresFa = $dom->importNode($this->okresFa->toDom()->documentElement, true);

        $okresFaGroup->appendChild($okresFa);

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        $data['OkresFa'] = OkresFa::normalizeXmlArray($data['OkresFa']);

        return Arr::onlyClassParameters($data, self::class);
    }
}
