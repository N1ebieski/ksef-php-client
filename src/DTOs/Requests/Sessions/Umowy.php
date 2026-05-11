<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\DataUmowy;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\NrUmowy;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;

final class Umowy extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    public function __construct(
        public readonly Optional | DataUmowy $dataUmowy = new Optional(),
        public readonly Optional | NrUmowy $nrUmowy = new Optional(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $umowy = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Umowy');
        $dom->appendChild($umowy);

        if ($this->dataUmowy instanceof DataUmowy) {
            $dataUmowy = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'DataUmowy');
            $dataUmowy->appendChild($dom->createTextNode((string) $this->dataUmowy));

            $umowy->appendChild($dataUmowy);
        }

        if ($this->nrUmowy instanceof NrUmowy) {
            $nrUmowy = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'NrUmowy');
            $nrUmowy->appendChild($dom->createTextNode((string) $this->nrUmowy));

            $umowy->appendChild($nrUmowy);
        }

        return $dom;
    }

    public static function fromXmlArray(array $data): self
    {
        return new self(
            dataUmowy: isset($data['DataUmowy']) ? new DataUmowy($data['DataUmowy']) : new Optional(),
            nrUmowy: isset($data['NrUmowy']) ? new NrUmowy($data['NrUmowy']) : new Optional(),
        );
    }
}
