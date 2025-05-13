<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\AdresL1;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\AdresL2;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\KodKraju;
use N1ebieski\KSEFClient\Resources\Online\ValueObjects\XmlNamespace;
use N1ebieski\KSEFClient\Support\DTO;

final readonly class Adres extends DTO implements DomSerializableInterface
{
    public function __construct(
        public AdresL1 $adresL1,
        public KodKraju $kodKraju = new KodKraju('PL'),
        public ?AdresL2 $adresL2 = null,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $adres = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'Adres');
        $dom->appendChild($adres);

        $kodKraju = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'KodKraju');
        $kodKraju->appendChild($dom->createTextNode((string) $this->kodKraju));

        $adres->appendChild($kodKraju);

        $adresL1 = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'AdresL1');
        $adresL1->appendChild($dom->createTextNode((string) $this->adresL1));

        $adres->appendChild($adresL1);

        if ($this->adresL2 !== null) {
            $adresL2 = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'AdresL2');
            $adresL2->appendChild($dom->createTextNode((string) $this->adresL2));
            $adres->appendChild($adresL2);
        }

        return $dom;
    }
}
