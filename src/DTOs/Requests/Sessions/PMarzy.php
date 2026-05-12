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

final class PMarzy extends AbstractDTO implements DomSerializableInterface, XmlNormalizableInterface
{
    public function __construct(
        public readonly P_PMarzyGroup | P_PMarzyNGroup $p_PMarzyGroup = new P_PMarzyNGroup(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $pMarzy = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'PMarzy');
        $dom->appendChild($pMarzy);

        /** @var DOMElement $p_PMarzyGroup */
        $p_PMarzyGroup = $this->p_PMarzyGroup->toDom()->documentElement;

        foreach ($p_PMarzyGroup->childNodes as $child) {
            $pMarzy->appendChild($dom->importNode($child, true));
        }

        $dom->appendChild($pMarzy);

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        $data['P_PMarzyGroup'] = match (true) {
            isset($data['P_PMarzy']) => P_PMarzyGroup::normalizeXmlArray($data),
            default => P_PMarzyNGroup::normalizeXmlArray($data),
        };

        return Arr::only($data, ['P_PMarzyGroup']);
    }
}
