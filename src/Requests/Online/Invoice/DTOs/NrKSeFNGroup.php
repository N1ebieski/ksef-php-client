<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\NrKSeFN;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class NrKSeFNGroup extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param NrKSeFN $nrKSeFN Znacznik faktury korygowanej wystawionej poza KSeF
     */
    public function __construct(
        public NrKSeFN $nrKSeFN = NrKSeFN::Default
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $nrKSeFNgroup = $dom->createElement('NrKSeFNGroup');
        $dom->appendChild($nrKSeFNgroup);

        $nrKSeFN = $dom->createElement('NrKSeFN');
        $nrKSeFN->appendChild($dom->createTextNode((string) $this->nrKSeFN->value));

        $nrKSeFNgroup->appendChild($nrKSeFN);

        return $dom;
    }
}
