<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use DOMElement;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_19;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class P_19Group extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param P_19 $p_19 Znacznik dostawy towarów lub świadczenia usług zwolnionych od podatku na podstawie art. 43 ust. 1, art. 113 ust. 1 i 9 albo przepisów wydanych na podstawie art. 82 ust. 3 ustawy lub na podstawie innych przepisów
     * @return void
     */
    public function __construct(
        public P_19AGroup | P_19BGroup | P_19CGroup $p_19ABCGroup,
        public P_19 $p_19 = P_19::Default,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_19Group = $dom->createElement('P_19Group');
        $dom->appendChild($p_19Group);

        $p_19 = $dom->createElement('P_19');
        $p_19->appendChild($dom->createTextNode((string) $this->p_19->value));

        $p_19Group->appendChild($p_19);

        /** @var DOMElement $p_19ABCGroup */
        $p_19ABCGroup = $this->p_19ABCGroup->toDom()->documentElement;

        foreach ($p_19ABCGroup->childNodes as $child) {
            $p_19Group->appendChild($dom->importNode($child, true));
        }

        return $dom;
    }
}
