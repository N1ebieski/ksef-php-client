<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\KodKraju;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\NrID;
use N1ebieski\KSEFClient\Requests\Online\ValueObjects\XmlNamespace;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class KrajGroup extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param NrID $nrId Dane identyfikujące nabywcę
     * @param null|KodKraju $kodKraju Kod (prefiks) nabywcy VAT UE, o którym mowa w art. 106e ust. 1 pkt 24 ustawy oraz w przypadku, o którym mowa w art. 136 ust. 1 pkt 4 ustawy
     * @return void
     */
    public function __construct(
        public NrID $nrId,
        public ?KodKraju $kodKraju = null
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $krajGroup = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'KrajGroup');
        $dom->appendChild($krajGroup);

        $nrId = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'NrID');
        $nrId->appendChild($dom->createTextNode((string) $this->nrId));

        $krajGroup->appendChild($nrId);

        if ($this->kodKraju instanceof KodKraju) {
            $kodKraju = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'KodKraju');
            $kodKraju->appendChild($dom->createTextNode((string) $this->kodKraju));
            $krajGroup->appendChild($kodKraju);
        }

        return $dom;
    }
}
