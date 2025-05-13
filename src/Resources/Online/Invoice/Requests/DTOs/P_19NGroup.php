<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_19N;
use N1ebieski\KSEFClient\Resources\Online\ValueObjects\XmlNamespace;
use N1ebieski\KSEFClient\Support\DTO;

final readonly class P_19NGroup extends DTO implements DomSerializableInterface
{
    /**
     * @param P_19N $p_19n Znacznik braku dostawy towarów lub świadczenia usług zwolnionych od podatku na podstawie art. 43 ust. 1, art. 113 ust. 1 i 9 ustawy albo przepisów wydanych na podstawie art. 82 ust. 3 ustawy lub na podstawie innych przepisów
     * @return void
     */
    public function __construct(
        public P_19N $p_19n = P_19N::Default,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_19ngroup = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'P_19NGroup');
        $dom->appendChild($p_19ngroup);

        $p_19n = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'P_19N');
        $p_19n->appendChild($dom->createTextNode((string) $this->p_19n->value));

        $p_19ngroup->appendChild($p_19n);

        return $dom;
    }
}
