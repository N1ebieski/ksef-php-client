<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\NrKSeFN;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final class NrKSeFNGroup extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    /**
     * @param NrKSeFN $nrKSeFN Znacznik faktury korygowanej wystawionej poza KSeF
     */
    public function __construct(
        public readonly NrKSeFN $nrKSeFN = NrKSeFN::Default
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $nrKSeFNGroup = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'NrKSeFNGroup');
        $dom->appendChild($nrKSeFNGroup);

        $nrKSeFN = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'NrKSeFN');
        $nrKSeFN->appendChild($dom->createTextNode((string) $this->nrKSeFN->value));

        $nrKSeFNGroup->appendChild($nrKSeFN);

        return $dom;
    }

    public static function fromXmlArray(array $data): self
    {
        return new self(
            nrKSeFN: isset($data['NrKSeFN']) ? NrKSeFN::from($data['NrKSeFN']) : NrKSeFN::Default,
        );
    }
}
