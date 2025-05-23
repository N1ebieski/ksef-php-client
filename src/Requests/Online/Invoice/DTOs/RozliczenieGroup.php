<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class RozliczenieGroup extends AbstractDTO implements DomSerializableInterface
{
    public function __construct(
        public DoZaplatyGroup|DoRozliczeniaGroup $doGroup
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $rozliczenieGroup = $dom->createElement('RozliczenieGroup');
        $dom->appendChild($rozliczenieGroup);

        /** @var DOMElement $doGroup */
        $doGroup = $this->doGroup->toDom()->documentElement;

        foreach ($doGroup->childNodes as $child) {
            $rozliczenieGroup->appendChild($dom->importNode($child, true));
        }

        return $dom;
    }
}
