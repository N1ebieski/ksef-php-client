<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;
use DOMElement;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final class RozliczenieGroup extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    public function __construct(
        public readonly DoZaplatyGroup|DoRozliczeniaGroup $doGroup
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $rozliczenieGroup = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'RozliczenieGroup');
        $dom->appendChild($rozliczenieGroup);

        /** @var DOMElement $doGroup */
        $doGroup = $this->doGroup->toDom()->documentElement;

        foreach ($doGroup->childNodes as $child) {
            $rozliczenieGroup->appendChild($dom->importNode($child, true));
        }

        return $dom;
    }

    public static function fromXmlArray(array $data): self
    {
        if (isset($data['DoZaplaty'])) {
            $doGroup = DoZaplatyGroup::fromXmlArray($data);
        } else {
            $doGroup = DoRozliczeniaGroup::fromXmlArray($data);
        }

        return new self(doGroup: $doGroup);
    }
}
