<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\XmlNormalizableInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_PMarzy_2;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class P_PMarzy_2Group extends AbstractDTO implements DomSerializableInterface, XmlNormalizableInterface
{
    /**
     * @param P_PMarzy_2 $p_PMarzy_2 Znacznik świadczenia usług turystyki, dla których podstawę opodatkowania stanowi marża, zgodnie z art. 119 ust. 1 ustawy, a faktura dokumentująca świadczenie zawiera wyrazy "procedura marży dla biur podróży"
     */
    public function __construct(
        public readonly P_PMarzy_2 $p_PMarzy_2 = P_PMarzy_2::Default,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_PMarzy_2Group = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_PMarzy_2Group');
        $dom->appendChild($p_PMarzy_2Group);

        $p_PMarzy_2 = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_PMarzy_2');
        $p_PMarzy_2->appendChild($dom->createTextNode((string) $this->p_PMarzy_2->value));

        $p_PMarzy_2Group->appendChild($p_PMarzy_2);

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        return Arr::only($data, ['P_PMarzy_2']);
    }
}
