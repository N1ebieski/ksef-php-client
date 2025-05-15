<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\Klucz;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\NrWiersza;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\Wartosc;
use N1ebieski\KSEFClient\Requests\Online\ValueObjects\XmlNamespace;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class DodatkowyOpis extends AbstractDTO implements DomSerializableInterface
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

        if ($this->nrWiersza instanceof NrWiersza) {
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
