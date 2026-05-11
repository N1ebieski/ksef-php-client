<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;
use DOMElement;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final class FakturaZaliczkowa extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    public function __construct(
        public readonly NrKSeFZNGroup | NrKSeFFaZaliczkowejGroup $nrKSeFZNGroup
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $fakturaZaliczkowa = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'FakturaZaliczkowa');
        $dom->appendChild($fakturaZaliczkowa);

        /** @var DOMElement $nrKSeFZNGroup */
        $nrKSeFZNGroup = $this->nrKSeFZNGroup->toDom()->documentElement;

        foreach ($nrKSeFZNGroup->childNodes as $child) {
            $fakturaZaliczkowa->appendChild($dom->importNode($child, true));
        }

        return $dom;
    }

    public static function fromXmlArray(array $data): self
    {
        if (isset($data['NrFaZaliczkowej'])) {
            $nrKSeFZNGroup = NrKSeFZNGroup::fromXmlArray($data);
        } else {
            $nrKSeFZNGroup = NrKSeFFaZaliczkowejGroup::fromXmlArray($data);
        }

        return new self(nrKSeFZNGroup: $nrKSeFZNGroup);
    }
}
