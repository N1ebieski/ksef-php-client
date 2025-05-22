<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\Klucz;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\NrWiersza;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\Wartosc;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;

final readonly class DodatkowyOpis extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param NrWiersza|Optional $nrWiersza Numer wiersza podany w polu NrWierszaFa lub NrWierszaZam, jeśli informacja odnosi się wyłącznie do danej pozycji faktury
     * @return void
     */
    public function __construct(
        public Klucz $klucz,
        public Wartosc $wartosc,
        public Optional | NrWiersza $nrWiersza = new Optional(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $dodatkowyOpis = $dom->createElement('DodatkowyOpis');
        $dom->appendChild($dodatkowyOpis);

        if ($this->nrWiersza instanceof NrWiersza) {
            $nrWiersza = $dom->createElement('NrWiersza');
            $nrWiersza->appendChild($dom->createTextNode((string) $this->nrWiersza));
            $dodatkowyOpis->appendChild($nrWiersza);
        }

        $klucz = $dom->createElement('Klucz');
        $klucz->appendChild($dom->createTextNode((string) $this->klucz));

        $dodatkowyOpis->appendChild($klucz);

        $wartosc = $dom->createElement('Wartosc');
        $wartosc->appendChild($dom->createTextNode((string) $this->wartosc));

        $dodatkowyOpis->appendChild($wartosc);

        $dom->appendChild($dodatkowyOpis);

        return $dom;
    }
}
