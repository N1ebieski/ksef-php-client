<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\DataZamowienia;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\NrZamowienia;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;

final readonly class Zamowienia extends AbstractDTO implements DomSerializableInterface
{
    public function __construct(
        public Optional | DataZamowienia $dataZamowienia = new Optional(),
        public Optional | NrZamowienia $nrZamowienia = new Optional(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $zamowienia = $dom->createElement('Zamowienia');
        $dom->appendChild($zamowienia);

        if ($this->dataZamowienia instanceof DataZamowienia) {
            $dataZamowienia = $dom->createElement('DataZamowienia');
            $dataZamowienia->appendChild($dom->createTextNode((string) $this->dataZamowienia));

            $zamowienia->appendChild($dataZamowienia);
        }

        if ($this->nrZamowienia instanceof NrZamowienia) {
            $nrZamowienia = $dom->createElement('NrZamowienia');
            $nrZamowienia->appendChild($dom->createTextNode((string) $this->nrZamowienia));

            $zamowienia->appendChild($nrZamowienia);
        }

        $dom->appendChild($zamowienia);

        return $dom;
    }
}
