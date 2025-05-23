<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\DataUmowy;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\NrUmowy;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;

final readonly class Umowy extends AbstractDTO implements DomSerializableInterface
{
    public function __construct(
        public Optional | DataUmowy $dataUmowy = new Optional(),
        public Optional | NrUmowy $nrUmowy = new Optional(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $umowy = $dom->createElement('Umowy');
        $dom->appendChild($umowy);

        if ($this->dataUmowy instanceof DataUmowy) {
            $dataUmowy = $dom->createElement('DataUmowy');
            $dataUmowy->appendChild($dom->createTextNode((string) $this->dataUmowy));

            $umowy->appendChild($dataUmowy);
        }

        if ($this->nrUmowy instanceof NrUmowy) {
            $nrUmowy = $dom->createElement('NrUmowy');
            $nrUmowy->appendChild($dom->createTextNode((string) $this->nrUmowy));

            $umowy->appendChild($nrUmowy);
        }

        return $dom;
    }
}
