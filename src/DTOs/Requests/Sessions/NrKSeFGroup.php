<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\NrKSeF;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\NrKSeFFaKorygowanej;

final class NrKSeFGroup extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    /**
     * @param NrKSeFFaKorygowanej $nrKSeFFaKorygowanej Numer identyfikujący fakturę korygowaną w KSeF
     * @param NrKSeF $nrKSeF Znacznik numeru KSeF faktury korygowanej
     */
    public function __construct(
        public readonly NrKSeFFaKorygowanej $nrKSeFFaKorygowanej,
        public readonly NrKSeF $nrKSeF = NrKSeF::Default
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $nrKSeFGroup = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'NrKSeFGroup');
        $dom->appendChild($nrKSeFGroup);

        $nrKSeF = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'NrKSeF');
        $nrKSeF->appendChild($dom->createTextNode((string) $this->nrKSeF->value));

        $nrKSeFGroup->appendChild($nrKSeF);

        $nrKSeFFaKorygowanej = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'NrKSeFFaKorygowanej');
        $nrKSeFFaKorygowanej->appendChild($dom->createTextNode((string) $this->nrKSeFFaKorygowanej));

        $nrKSeFGroup->appendChild($nrKSeFFaKorygowanej);

        return $dom;
    }

    public static function fromXmlArray(array $data): self
    {
        return new self(
            nrKSeFFaKorygowanej: new NrKSeFFaKorygowanej($data['NrKSeFFaKorygowanej']),
            nrKSeF: isset($data['NrKSeF']) ? NrKSeF::from($data['NrKSeF']) : NrKSeF::Default,
        );
    }
}
