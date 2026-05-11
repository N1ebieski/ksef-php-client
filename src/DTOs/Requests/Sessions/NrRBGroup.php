<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\NrRB;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\SWIFT;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;

final class NrRBGroup extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    /**
     * @param NrRB $nrRB Pełny numer rachunku
     * @param Optional|SWIFT $swift Kod SWIFT
     */
    public function __construct(
        public readonly NrRB $nrRB,
        public readonly Optional | SWIFT $swift = new Optional(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $nrRBGroup = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'NrRBGroup');
        $dom->appendChild($nrRBGroup);

        $nrRB = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'NrRB');
        $nrRB->appendChild($dom->createTextNode((string) $this->nrRB));

        $nrRBGroup->appendChild($nrRB);

        if ($this->swift instanceof SWIFT) {
            $swift = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'SWIFT');
            $swift->appendChild($dom->createTextNode((string) $this->swift));

            $nrRBGroup->appendChild($swift);
        }

        return $dom;
    }

    public static function fromXmlArray(array $data): self
    {
        return new self(
            nrRB: new NrRB($data['NrRB']),
            swift: isset($data['SWIFT']) ? new SWIFT($data['SWIFT']) : new Optional(),
        );
    }
}
