<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\NrRB;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\SWIFT;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;

final readonly class NrRBGroup extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param NrRB $nrRB Pełny numer rachunku
     * @param Optional|SWIFT $swift Kod SWIFT
     */
    public function __construct(
        public NrRB $nrRB,
        public Optional | SWIFT $swift = new Optional(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $nrRBGroup = $dom->createElement('NrRBGroup');
        $dom->appendChild($nrRBGroup);

        $nrRB = $dom->createElement('NrRB');
        $nrRB->appendChild($dom->createTextNode((string) $this->nrRB));

        $nrRBGroup->appendChild($nrRB);

        if ($this->swift instanceof SWIFT) {
            $swift = $dom->createElement('SWIFT');
            $swift->appendChild($dom->createTextNode((string) $this->swift));

            $nrRBGroup->appendChild($swift);
        }

        return $dom;
    }
}
