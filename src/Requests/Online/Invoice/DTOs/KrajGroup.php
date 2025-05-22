<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\KodKraju;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\NrID;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;

final readonly class KrajGroup extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param NrID $nrID Dane identyfikujące nabywcę
     * @param Optional|KodKraju $kodKraju Kod (prefiks) nabywcy VAT UE, o którym mowa w art. 106e ust. 1 pkt 24 ustawy oraz w przypadku, o którym mowa w art. 136 ust. 1 pkt 4 ustawy
     * @return void
     */
    public function __construct(
        public NrID $nrID,
        public Optional | KodKraju $kodKraju = new Optional()
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $krajGroup = $dom->createElement('KrajGroup');
        $dom->appendChild($krajGroup);

        $nrID = $dom->createElement('NrID');
        $nrID->appendChild($dom->createTextNode((string) $this->nrID));

        $krajGroup->appendChild($nrID);

        if ($this->kodKraju instanceof KodKraju) {
            $kodKraju = $dom->createElement('KodKraju');
            $kodKraju->appendChild($dom->createTextNode((string) $this->kodKraju));
            $krajGroup->appendChild($kodKraju);
        }

        return $dom;
    }
}
