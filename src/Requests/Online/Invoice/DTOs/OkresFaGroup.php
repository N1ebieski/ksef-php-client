<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\ValueObjects\XmlNamespace;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class OkresFaGroup extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param OkresFa $okresFa Okres, którego dotyczy faktura w przypadkach, o których mowa w art. 19a ust. 3 zdanie pierwsze i ust. 4 oraz ust. 5 pkt 4 ustawy
     * @return void
     */
    public function __construct(
        public OkresFa $okresFa,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $okresFaGroup = $dom->createElement('OkresFaGroup');
        $dom->appendChild($okresFaGroup);

        $okresFa = $dom->importNode($this->okresFa->toDom()->documentElement, true);

        $okresFaGroup->appendChild($okresFa);

        return $dom;
    }
}
