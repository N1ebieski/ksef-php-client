<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_PMarzy_3_3;
use N1ebieski\KSEFClient\Resources\Online\ValueObjects\XmlNamespace;
use N1ebieski\KSEFClient\Support\DTO;

final readonly class P_PMarzy_3_3Group extends DTO implements DomSerializableInterface
{
    /**
     * @param P_PMarzy_3_3 $p_pmarzy_3_3 Znacznik dostawy przedmiotów kolekcjonerskich i antyków, dla których podstawę opodatkowania stanowi marża, zgodnie z art. 120 ustawy, a faktura dokumentująca dostawę zawiera wyrazy "procedura marży - przedmioty kolekcjonerskie i antyki"
     * @return void
     */
    public function __construct(
        public P_PMarzy_3_3 $p_pmarzy_3_3 = P_PMarzy_3_3::Default,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_pmarzy_3_3group = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'P_PMarzy_3_3Group');
        $dom->appendChild($p_pmarzy_3_3group);

        $p_pmarzy_3_3 = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'P_PMarzy_3_3');
        $p_pmarzy_3_3->appendChild($dom->createTextNode((string) $this->p_pmarzy_3_3->value));

        $p_pmarzy_3_3group->appendChild($p_pmarzy_3_3);

        return $dom;
    }
}
