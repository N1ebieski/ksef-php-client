<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use DOMElement;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class Zwolnienie extends AbstractDTO implements DomSerializableInterface
{
    public function __construct(
        public P_19Group | P_19NGroup $p_19Group = new P_19NGroup(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $zwolnienie = $dom->createElement('Zwolnienie');
        $dom->appendChild($zwolnienie);

        /** @var DOMElement $p_19Group */
        $p_19Group = $this->p_19Group->toDom()->documentElement;

        foreach ($p_19Group->childNodes as $child) {
            $zwolnienie->appendChild($dom->importNode($child, true));
        }

        $dom->appendChild($zwolnienie);

        return $dom;
    }
}
