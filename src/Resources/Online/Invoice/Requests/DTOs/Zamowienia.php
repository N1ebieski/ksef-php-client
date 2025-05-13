<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\DataZamowienia;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\NrZamowienia;
use N1ebieski\KSEFClient\Resources\Online\ValueObjects\XmlNamespace;
use N1ebieski\KSEFClient\Support\DTO;

final readonly class Zamowienia extends DTO implements DomSerializableInterface
{
    public function __construct(
        public ?DataZamowienia $dataZamowienia = null,
        public ?NrZamowienia $nrZamowienia = null,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $zamowienia = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'Zamowienia');
        $dom->appendChild($zamowienia);

        if ($this->dataZamowienia !== null) {
            $dataZamowienia = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'DataZamowienia');
            $dataZamowienia->appendChild($dom->createTextNode((string) $this->dataZamowienia));

            $zamowienia->appendChild($dataZamowienia);
        }

        if ($this->nrZamowienia !== null) {
            $nrZamowienia = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'NrZamowienia');
            $nrZamowienia->appendChild($dom->createTextNode((string) $this->nrZamowienia));

            $zamowienia->appendChild($nrZamowienia);
        }

        $dom->appendChild($zamowienia);

        return $dom;
    }
}
