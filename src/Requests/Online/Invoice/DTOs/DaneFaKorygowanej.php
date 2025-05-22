<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use DOMElement;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\DataWystFaKorygowanej;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\NrFaKorygowanej;

final readonly class DaneFaKorygowanej implements DomSerializableInterface
{
    /**
     * @param DataWystFaKorygowanej $dataWystFaKorygowanej Data wystawienia faktury korygowanej
     * @param NrFaKorygowanej $nrFaKorygowanej Numer faktury korygowanej
     */
    public function __construct(
        public DataWystFaKorygowanej $dataWystFaKorygowanej,
        public NrFaKorygowanej $nrFaKorygowanej,
        public NrKSeFGroup | NrKSeFNGroup $nrKSeFGroup
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $daneFaKorygowanej = $dom->createElement('DaneFaKorygowanej');
        $dom->appendChild($daneFaKorygowanej);

        $dataWystFaKorygowanej = $dom->createElement('DataWystFaKorygowanej');
        $dataWystFaKorygowanej->appendChild($dom->createTextNode((string) $this->dataWystFaKorygowanej));

        $daneFaKorygowanej->appendChild($dataWystFaKorygowanej);

        $nrFaKorygowanej = $dom->createElement('NrFaKorygowanej');
        $nrFaKorygowanej->appendChild($dom->createTextNode((string) $this->nrFaKorygowanej));

        $daneFaKorygowanej->appendChild($nrFaKorygowanej);

        /** @var DOMElement $nrKSeFGroup */
        $nrKSeFGroup = $this->nrKSeFGroup->toDom()->documentElement;

        foreach ($nrKSeFGroup->childNodes as $child) {
            $daneFaKorygowanej->appendChild($dom->importNode($child, true));
        }

        return $dom;
    }
}
