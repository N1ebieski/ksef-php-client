<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use DOMElement;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class FakturaZaliczkowa extends AbstractDTO implements DomSerializableInterface
{
    public function __construct(
        public NrKSeFZNGroup | NrKSeFFaZaliczkowejGroup $nrKSeFZNGroup
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $fakturaZaliczkowa = $dom->createElement('FakturaZaliczkowa');
        $dom->appendChild($fakturaZaliczkowa);

        /** @var DOMElement $nrKSeFZNGroup */
        $nrKSeFZNGroup = $this->nrKSeFZNGroup->toDom()->documentElement;

        foreach ($nrKSeFZNGroup->childNodes as $child) {
            $fakturaZaliczkowa->appendChild($dom->importNode($child, true));
        }

        return $dom;
    }
}
