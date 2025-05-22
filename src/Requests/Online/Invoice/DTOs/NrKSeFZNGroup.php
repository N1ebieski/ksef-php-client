<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\NrFaZaliczkowej;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\NrKSeFZN;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class NrKSeFZNGroup extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param NrFaZaliczkowej $nrFaZaliczkowej Numer faktury zaliczkowej wystawionej poza KSeF. Pole obowiązkowe dla faktury wystawianej po wydaniu towaru lub wykonaniu usługi, o której mowa w art. 106f ust. 3 ustawy i ostatniej z faktur, o której mowa w art. 106f ust. 4 ustawy
     * @param NrKSeFZN $nrKSeFZN Znacznik faktury zaliczkowej wystawionej poza KSeF
     */
    public function __construct(
        public NrFaZaliczkowej $nrFaZaliczkowej,
        public NrKSeFZN $nrKSeFZN = NrKSeFZN::Default
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $nrKSeFZNGroup = $dom->createElement('NrKSeFZNGroup');
        $dom->appendChild($nrKSeFZNGroup);

        $nrKSeFZN = $dom->createElement('NrKSeFZN');
        $nrKSeFZN->appendChild($dom->createTextNode((string) $this->nrKSeFZN->value));

        $nrKSeFZNGroup->appendChild($nrKSeFZN);

        $nrFaZaliczkowej = $dom->createElement('NrFaZaliczkowej');
        $nrFaZaliczkowej->appendChild($dom->createTextNode((string) $this->nrFaZaliczkowej));

        $nrKSeFZNGroup->appendChild($nrFaZaliczkowej);

        return $dom;
    }
}
