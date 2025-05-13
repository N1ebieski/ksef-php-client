<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\KodKraju;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\KodUE;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\Nazwa;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\NrID;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\NrVatUE;
use N1ebieski\KSEFClient\Resources\Online\ValueObjects\XmlNamespace;
use N1ebieski\KSEFClient\Support\DTO;
use N1ebieski\KSEFClient\ValueObjects\NIP;

final readonly class KrajGroup extends DTO implements DomSerializableInterface
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

        if ($this->kodKraju !== null) {
            $kodKraju = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'KodKraju');
            $kodKraju->appendChild($dom->createTextNode((string) $this->kodKraju));
            $krajGroup->appendChild($kodKraju);
        }

        return $dom;
    }
}
