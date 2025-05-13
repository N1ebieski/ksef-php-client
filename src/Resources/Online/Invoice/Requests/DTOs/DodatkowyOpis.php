<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\Klucz;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\NrWiersza;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\Wartosc;
use N1ebieski\KSEFClient\Resources\Online\ValueObjects\XmlNamespace;
use N1ebieski\KSEFClient\Support\DTO;

final readonly class DodatkowyOpis extends DTO implements DomSerializableInterface
{
    /**
     * @param NrWiersza|null $nrWiersza Numer wiersza podany w polu NrWierszaFa lub NrWierszaZam, jeśli informacja odnosi się wyłącznie do danej pozycji faktury
     * @return void
     */
    public function __construct(
        public Klucz $klucz,
        public Wartosc $wartosc,
        public ?NrWiersza $nrWiersza = null,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $dodatkowyOpis = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'DodatkowyOpis');
        $dom->appendChild($dodatkowyOpis);

        if ($this->nrWiersza !== null) {
            $nrWiersza = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'NrWiersza');
            $nrWiersza->appendChild($dom->createTextNode((string) $this->nrWiersza));
            $dodatkowyOpis->appendChild($nrWiersza);
        }

        $klucz = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'Klucz');
        $klucz->appendChild($dom->createTextNode((string) $this->klucz));
        $dodatkowyOpis->appendChild($klucz);

        $wartosc = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'Wartosc');
        $wartosc->appendChild($dom->createTextNode((string) $this->wartosc));
        $dodatkowyOpis->appendChild($wartosc);

        $dom->appendChild($dodatkowyOpis);

        return $dom;
    }
}
