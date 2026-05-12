<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use DOMElement;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\XmlNormalizableInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class FakturaZaliczkowa extends AbstractDTO implements DomSerializableInterface, XmlNormalizableInterface
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

    public static function normalizeXmlArray(array $data): array
    {
        $data['NrKSeFZNGroup'] = match (true) {
            isset($data['NrFaZaliczkowej']) => NrKSeFZNGroup::normalizeXmlArray($data),
            default => NrKSeFFaZaliczkowejGroup::normalizeXmlArray($data),
        };

        return Arr::only($data, ['NrKSeFZNGroup']);
    }
}
