<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\KodUE;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\NrVatUE;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class UEGroup extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    /**
     * @param KodUE $kodUE Kod (prefiks) nabywcy VAT UE, o którym mowa w art. 106e ust. 1 pkt 24 ustawy oraz w przypadku, o którym mowa w art. 136 ust. 1 pkt 4 ustawy
     * @param NrVatUE $nrVatUE Numer Identyfikacyjny VAT kontrahenta UE
     */
    public function __construct(
        public readonly KodUE $kodUE,
        public readonly NrVatUE $nrVatUE
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $ueGroup = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'UEGroup');
        $dom->appendChild($ueGroup);

        $kodUE = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'KodUE');
        $kodUE->appendChild($dom->createTextNode((string) $this->kodUE));

        $ueGroup->appendChild($kodUE);

        $nrVatUE = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'NrVatUE');
        $nrVatUE->appendChild($dom->createTextNode((string) $this->nrVatUE));

        $ueGroup->appendChild($nrVatUE);

        return $dom;
    }

    public static function fromXmlArray(array $data): self
    {
        return new self(
            kodUE: new KodUE($data['KodUE']),
            nrVatUE: new NrVatUE($data['NrVatUE']),
        );
    }
}
