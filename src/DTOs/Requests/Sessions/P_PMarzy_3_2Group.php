<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_PMarzy_3_2;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class P_PMarzy_3_2Group extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    /**
     * @param P_PMarzy_3_2 $p_PMarzy_3_2 Znacznik dostawy dzieł sztuki dla których podstawę opodatkowania stanowi marża, zgodnie z art. 120 ustawy, a faktura dokumentująca dostawę zawiera wyrazy "procedura marży - dzieła sztuki"
     */
    public function __construct(
        public readonly P_PMarzy_3_2 $p_PMarzy_3_2 = P_PMarzy_3_2::Default,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_PMarzy_3_2Group = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_PMarzy_3_2Group');
        $dom->appendChild($p_PMarzy_3_2Group);

        $p_PMarzy_3_2 = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_PMarzy_3_2');
        $p_PMarzy_3_2->appendChild($dom->createTextNode((string) $this->p_PMarzy_3_2->value));

        $p_PMarzy_3_2Group->appendChild($p_PMarzy_3_2);

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        return Arr::onlyClassParameters($data, self::class);
    }
}
