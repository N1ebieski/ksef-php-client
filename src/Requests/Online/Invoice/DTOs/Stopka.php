<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\ValueObjects\XmlNamespace;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class Stopka extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param array<int, Informacje> $informacje Pozostałe dane
     * @param array<int, Rejestry> $rejestry
     * @return void
     */
    public function __construct(
        public array $informacje = [],
        public array $rejestry = []
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $stopka = $dom->createElement('Stopka');
        $dom->appendChild($stopka);

        foreach ($this->informacje as $informacje) {
            $informacje = $dom->importNode($informacje->toDom()->documentElement, true);
            $stopka->appendChild($informacje);
        }

        foreach ($this->rejestry as $rejestry) {
            $rejestry = $dom->importNode($rejestry->toDom()->documentElement, true);
            $stopka->appendChild($rejestry);
        }

        return $dom;
    }
}
