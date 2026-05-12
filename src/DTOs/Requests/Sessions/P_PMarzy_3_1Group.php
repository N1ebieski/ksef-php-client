<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\XmlNormalizableInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_PMarzy_3_1;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class P_PMarzy_3_1Group extends AbstractDTO implements DomSerializableInterface, XmlNormalizableInterface
{
    /**
     * @param P_PMarzy_3_1 $p_PMarzy_3_1 Znacznik dostawy towarów używanych dla których podstawę opodatkowania stanowi marża, zgodnie z art. 120 ustawy, a faktura dokumentująca dostawę zawiera wyrazy "procedura marży - towary używane"
     */
    public function __construct(
        public readonly P_PMarzy_3_1 $p_PMarzy_3_1 = P_PMarzy_3_1::Default,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_PMarzy_3_1Group = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_PMarzy_3_1Group');
        $dom->appendChild($p_PMarzy_3_1Group);

        $p_PMarzy_3_1 = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_PMarzy_3_1');
        $p_PMarzy_3_1->appendChild($dom->createTextNode((string) $this->p_PMarzy_3_1->value));

        $p_PMarzy_3_1Group->appendChild($p_PMarzy_3_1);

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        return Arr::only($data, ['P_PMarzy_3_1']);
    }
}
