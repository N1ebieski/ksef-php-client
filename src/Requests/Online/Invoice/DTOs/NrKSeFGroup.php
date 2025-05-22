<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\NrKSeF;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\NrKSeFFaKorygowanej;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class NrKSeFGroup extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param NrKSeFFaKorygowanej $nrKSeFFaKorygowanej Numer identyfikujący fakturę korygowaną w KSeF
     * @param NrKSeF $nrKSeF Znacznik numeru KSeF faktury korygowanej
     */
    public function __construct(
        public NrKSeFFaKorygowanej $nrKSeFFaKorygowanej,
        public NrKSeF $nrKSeF = NrKSeF::Default
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $nrKSeFgroup = $dom->createElement('NrKSeFGroup');
        $dom->appendChild($nrKSeFgroup);

        $nrKSeF = $dom->createElement('NrKSeF');
        $nrKSeF->appendChild($dom->createTextNode((string) $this->nrKSeF->value));

        $nrKSeFgroup->appendChild($nrKSeF);

        $nrKSeFFaKorygowanej = $dom->createElement('NrKSeFFaKorygowanej');
        $nrKSeFFaKorygowanej->appendChild($dom->createTextNode((string) $this->nrKSeFFaKorygowanej));

        $nrKSeFgroup->appendChild($nrKSeFFaKorygowanej);

        return $dom;
    }
}
