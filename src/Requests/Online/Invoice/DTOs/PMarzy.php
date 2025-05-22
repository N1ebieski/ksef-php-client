<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use DOMElement;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class PMarzy extends AbstractDTO implements DomSerializableInterface
{
    public function __construct(
        public P_PMarzyGroup | P_PMarzyNGroup $p_PMarzyGroup = new P_PMarzyNGroup(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $pMarzy = $dom->createElement('PMarzy');
        $dom->appendChild($pMarzy);

        /** @var DOMElement $p_PMarzyGroup */
        $p_PMarzyGroup = $this->p_PMarzyGroup->toDom()->documentElement;

        foreach ($p_PMarzyGroup->childNodes as $child) {
            $pMarzy->appendChild($dom->importNode($child, true));
        }

        $dom->appendChild($pMarzy);

        return $dom;
    }
}
