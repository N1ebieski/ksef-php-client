<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions\FakturaRR;

use DOMDocument;
use DOMElement;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\XmlNormalizableInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class RozliczenieGroup extends AbstractDTO implements DomSerializableInterface, XmlNormalizableInterface
{
    public function __construct(
        public readonly DoZaplatyGroup|DoRozliczeniaGroup $doGroup
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $rozliczenieGroup = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'RozliczenieGroup');
        $dom->appendChild($rozliczenieGroup);

        /** @var DOMElement $doGroup */
        $doGroup = $this->doGroup->toDom()->documentElement;

        foreach ($doGroup->childNodes as $child) {
            $rozliczenieGroup->appendChild($dom->importNode($child, true));
        }

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        $data['DoGroup'] = match (true) {
            isset($data['DoZaplaty']) => DoZaplatyGroup::normalizeXmlArray($data),
            default => DoRozliczeniaGroup::normalizeXmlArray($data),
        };

        return Arr::only($data, ['DoGroup']);
    }
}
