<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\XmlNormalizableInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_PMarzy_3_3;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class P_PMarzy_3_3Group extends AbstractDTO implements DomSerializableInterface, XmlNormalizableInterface
{
    /**
     * @param P_PMarzy_3_3 $p_PMarzy_3_3 Znacznik dostawy przedmiotów kolekcjonerskich i antyków, dla których podstawę opodatkowania stanowi marża, zgodnie z art. 120 ustawy, a faktura dokumentująca dostawę zawiera wyrazy "procedura marży - przedmioty kolekcjonerskie i antyki"
     */
    public function __construct(
        public readonly P_PMarzy_3_3 $p_PMarzy_3_3 = P_PMarzy_3_3::Default,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_PMarzy_3_3Group = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_PMarzy_3_3Group');
        $dom->appendChild($p_PMarzy_3_3Group);

        $p_PMarzy_3_3 = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_PMarzy_3_3');
        $p_PMarzy_3_3->appendChild($dom->createTextNode((string) $this->p_PMarzy_3_3->value));

        $p_PMarzy_3_3Group->appendChild($p_PMarzy_3_3);

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        return Arr::only($data, ['P_PMarzy_3_3']);
    }
}
