<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\KodUE;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\NrVatUE;
use N1ebieski\KSEFClient\Requests\Online\ValueObjects\XmlNamespace;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class UEGroup extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param KodUE $kodUe Kod (prefiks) nabywcy VAT UE, o którym mowa w art. 106e ust. 1 pkt 24 ustawy oraz w przypadku, o którym mowa w art. 136 ust. 1 pkt 4 ustawy
     * @param NrVatUE $nrVatUe Numer Identyfikacyjny VAT kontrahenta UE
     * @return void
     */
    public function __construct(
        public KodUE $kodUe,
        public NrVatUE $nrVatUe
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $ueGroup = $dom->createElement('UEGroup');
        $dom->appendChild($ueGroup);

        $kodUe = $dom->createElement('KodUE');
        $kodUe->appendChild($dom->createTextNode((string) $this->kodUe));

        $ueGroup->appendChild($kodUe);

        $nrVatUe = $dom->createElement('nrVatUe');
        $nrVatUe->appendChild($dom->createTextNode((string) $this->nrVatUe));

        $ueGroup->appendChild($nrVatUe);

        return $dom;
    }
}
