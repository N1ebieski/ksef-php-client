<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\XmlNormalizableInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\Klucz;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\NrWiersza;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\Wartosc;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class DodatkowyOpis extends AbstractDTO implements DomSerializableInterface, XmlNormalizableInterface
{
    /**
     * @param NrWiersza|Optional $nrWiersza Numer wiersza podany w polu NrWierszaFa lub NrWierszaZam, jeśli informacja odnosi się wyłącznie do danej pozycji faktury
     */
    public function __construct(
        public readonly Klucz $klucz,
        public readonly Wartosc $wartosc,
        public readonly Optional | NrWiersza $nrWiersza = new Optional(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $dodatkowyOpis = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'DodatkowyOpis');
        $dom->appendChild($dodatkowyOpis);

        if ($this->nrWiersza instanceof NrWiersza) {
            $nrWiersza = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'NrWiersza');
            $nrWiersza->appendChild($dom->createTextNode((string) $this->nrWiersza));
            $dodatkowyOpis->appendChild($nrWiersza);
        }

        $klucz = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Klucz');
        $klucz->appendChild($dom->createTextNode((string) $this->klucz));

        $dodatkowyOpis->appendChild($klucz);

        $wartosc = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Wartosc');
        $wartosc->appendChild($dom->createTextNode((string) $this->wartosc));

        $dodatkowyOpis->appendChild($wartosc);

        $dom->appendChild($dodatkowyOpis);

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        return Arr::only($data, ['Klucz', 'Wartosc', 'NrWiersza']);
    }
}
