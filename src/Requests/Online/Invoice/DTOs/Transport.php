<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use DOMElement;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\NrZleceniaTransportu;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;

final readonly class Transport extends AbstractDTO implements DomSerializableInterface
{
    public function __construct(
        public RodzajTransportuGroup | TransportInnyGroup $transportGroup,
        public LadunekGroup $ladunekGroup,
        public Optional | Przewoznik $przewoznik = new Optional(),
        public Optional | NrZleceniaTransportu $nrZleceniaTransportu = new Optional(),
        public Optional | WysylkaGroup $wysylkaGroup = new Optional(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $transport = $dom->createElement('Transport');
        $dom->appendChild($transport);

        /** @var DOMElement $transportGroup */
        $transportGroup = $dom->importNode($this->transportGroup->toDom()->documentElement, true);

        foreach ($transportGroup->childNodes as $child) {
            $transport->appendChild($dom->importNode($child, true));
        }

        $przewoznik = $dom->importNode($this->przewoznik->toDom()->documentElement, true);

        $transport->appendChild($przewoznik);

        if ($this->nrZleceniaTransportu instanceof NrZleceniaTransportu) {
            $nrZleceniaTransportu = $dom->createElement('NrZleceniaTransportu');
            $nrZleceniaTransportu->appendChild($dom->createTextNode((string) $this->nrZleceniaTransportu));

            $transport->appendChild($nrZleceniaTransportu);
        }

        /** @var DOMElement $ladunekGroup */
        $ladunekGroup = $dom->importNode($this->ladunekGroup->toDom()->documentElement, true);

        foreach ($ladunekGroup->childNodes as $child) {
            $transport->appendChild($dom->importNode($child, true));
        }

        if ($this->wysylkaGroup instanceof WysylkaGroup) {
            /** @var DOMElement $wysylkaGroup */
            $wysylkaGroup = $dom->importNode($this->wysylkaGroup->toDom()->documentElement, true);

            foreach ($wysylkaGroup->childNodes as $child) {
                $transport->appendChild($dom->importNode($child, true));
            }
        }

        return $dom;
    }
}
