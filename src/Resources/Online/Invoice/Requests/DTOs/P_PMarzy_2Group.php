<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\KodKraju;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\KodUE;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\Nazwa;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\NrID;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\NrVatUE;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_19N;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_22N;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_6;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_PMarzy;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_PMarzy_2;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_PMarzyN;
use N1ebieski\KSEFClient\Resources\Online\ValueObjects\XmlNamespace;
use N1ebieski\KSEFClient\Support\DTO;
use N1ebieski\KSEFClient\ValueObjects\NIP;

final readonly class P_PMarzy_2Group extends DTO implements DomSerializableInterface
{
    /**
     * @param P_PMarzy_2 $p_pmarzy_2 Znacznik świadczenia usług turystyki, dla których podstawę opodatkowania stanowi marża, zgodnie z art. 119 ust. 1 ustawy, a faktura dokumentująca świadczenie zawiera wyrazy "procedura marży dla biur podróży"
     * @return void
     */
    public function __construct(
        public P_PMarzy_2 $p_pmarzy_2 = P_PMarzy_2::Default,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_pmarzy_2group = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'P_PMarzy_2Group');
        $dom->appendChild($p_pmarzy_2group);

        $p_pmarzy_2 = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'P_PMarzy_2');
        $p_pmarzy_2->appendChild($dom->createTextNode((string) $this->p_pmarzy_2->value));

        $p_pmarzy_2group->appendChild($p_pmarzy_2);

        return $dom;
    }
}
