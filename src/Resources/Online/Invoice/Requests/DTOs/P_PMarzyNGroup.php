<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_PMarzyN;
use N1ebieski\KSEFClient\Resources\Online\ValueObjects\XmlNamespace;
use N1ebieski\KSEFClient\Support\DTO;

final readonly class P_PMarzyNGroup extends DTO implements DomSerializableInterface
{
    /**
     * @param P_PMarzyN $p_pmarzyn Znacznik braku wystąpienia procedur marży, o których mowa w art. 119 lub art. 120 ustawy
     * @return void
     */
    public function __construct(
        public P_PMarzyN $p_pmarzyn = P_PMarzyN::Default,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_pmarzyngroup = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'P_PMarzyNGroup');
        $dom->appendChild($p_pmarzyngroup);

        $p_pmarzyn = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'P_PMarzyN');
        $p_pmarzyn->appendChild($dom->createTextNode((string) $this->p_pmarzyn->value));

        $p_pmarzyngroup->appendChild($p_pmarzyn);

        return $dom;
    }
}
