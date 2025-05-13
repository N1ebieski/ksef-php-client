<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs;

use DOMDocument;
use DOMElement;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\KodKraju;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\KodUE;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\Nazwa;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\NrID;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\NrVatUE;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_19;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_19N;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_6;
use N1ebieski\KSEFClient\Resources\Online\ValueObjects\XmlNamespace;
use N1ebieski\KSEFClient\Support\DTO;
use N1ebieski\KSEFClient\ValueObjects\NIP;

final readonly class P_19Group extends DTO implements DomSerializableInterface
{
    /**
     * @param P_19 $p_19 Znacznik dostawy towarów lub świadczenia usług zwolnionych od podatku na podstawie art. 43 ust. 1, art. 113 ust. 1 i 9 albo przepisów wydanych na podstawie art. 82 ust. 3 ustawy lub na podstawie innych przepisów
     * @return void
     */
    public function __construct(
        public P_19 $p_19,
        public P_19AGroup | P_19BGroup | P_19CGroup $p_19abcgroup
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_19group = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'P_19Group');
        $dom->appendChild($p_19group);

        $p_19 = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'P_19');
        $p_19->appendChild($dom->createTextNode((string) $this->p_19->value));

        $p_19group->appendChild($p_19);

        /** @var DOMElement $p_19abcgroup */
        $p_19abcgroup = $this->p_19abcgroup->toDom()->documentElement;

        foreach ($p_19abcgroup->childNodes as $child) {
            $p_19group->appendChild($dom->importNode($child, true));
        }

        return $dom;
    }
}
