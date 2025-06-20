<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\NrKSeFFaZaliczkowej;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class NrKSeFFaZaliczkowejGroup extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param NrKSeFFaZaliczkowej $nrKSeFFaZaliczkowej Numer identyfikujący fakturę zaliczkową w KSeF. Pole obowiązkowe w przypadku, gdy faktura zaliczkowa była wystawiona za pomocą KSeF
     */
    public function __construct(
        public NrKSeFFaZaliczkowej $nrKSeFFaZaliczkowej
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $nrKSeFFaZaliczkowejGroup = $dom->createElement('NrKSeFFaZaliczkowejGroup');
        $dom->appendChild($nrKSeFFaZaliczkowejGroup);

        $nrKSeFFaZaliczkowej = $dom->createElement('NrKSeFFaZaliczkowej');
        $nrKSeFFaZaliczkowej->appendChild($dom->createTextNode($this->nrKSeFFaZaliczkowej->value));

        $nrKSeFFaZaliczkowejGroup->appendChild($nrKSeFFaZaliczkowej);

        return $dom;
    }
}
