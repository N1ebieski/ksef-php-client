<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\AdresL1;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\AdresL2;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\GLN;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\KodKraju;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;

final readonly class WysylkaZ extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param Optional|GLN $gln Globalny Numer Lokalizacyjny [Global Location Number]
     */
    public function __construct(
        public AdresL1 $adresL1,
        public KodKraju $kodKraju = new KodKraju('PL'),
        public Optional | AdresL2 $adresL2 = new Optional(),
        public Optional | GLN $gln = new Optional()
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $wysylkaZ = $dom->createElement('WysylkaZ');
        $dom->appendChild($wysylkaZ);

        $kodKraju = $dom->createElement('KodKraju');
        $kodKraju->appendChild($dom->createTextNode((string) $this->kodKraju));

        $wysylkaZ->appendChild($kodKraju);

        $adresL1 = $dom->createElement('AdresL1');
        $adresL1->appendChild($dom->createTextNode((string) $this->adresL1));

        $wysylkaZ->appendChild($adresL1);

        if ($this->adresL2 instanceof AdresL2) {
            $adresL2 = $dom->createElement('AdresL2');
            $adresL2->appendChild($dom->createTextNode((string) $this->adresL2));
            $wysylkaZ->appendChild($adresL2);
        }

        if ($this->gln instanceof GLN) {
            $gln = $dom->createElement('GLN');
            $gln->appendChild($dom->createTextNode((string) $this->gln));
            $wysylkaZ->appendChild($gln);
        }

        return $dom;
    }
}
