<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use DOMElement;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\XmlNormalizableInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_PMarzy;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class P_PMarzyGroup extends AbstractDTO implements DomSerializableInterface, XmlNormalizableInterface
{
    /**
     * @param P_PMarzy $p_PMarzy Znacznik wystąpienia procedur marży, o których mowa w art. 119 lub art. 120 ustawy
     */
    public function __construct(
        public readonly P_PMarzy_2Group | P_PMarzy_3_1Group | P_PMarzy_3_2Group | P_PMarzy_3_3Group $p_PMarzy_2_3Group,
        public readonly P_PMarzy $p_PMarzy = P_PMarzy::Default
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_PMarzyGroup = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_PMarzyGroup');
        $dom->appendChild($p_PMarzyGroup);

        $p_PMarzy = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_PMarzy');
        $p_PMarzy->appendChild($dom->createTextNode((string) $this->p_PMarzy->value));

        $p_PMarzyGroup->appendChild($p_PMarzy);

        /** @var DOMElement $p_PMarzy2_3Group */
        $p_PMarzy2_3Group = $this->p_PMarzy_2_3Group->toDom()->documentElement;

        foreach ($p_PMarzy2_3Group->childNodes as $child) {
            $p_PMarzyGroup->appendChild($dom->importNode($child, true));
        }

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        $data['P_PMarzy_2_3Group'] = match (true) {
            isset($data['P_PMarzy_2']) => P_PMarzy_2Group::normalizeXmlArray($data),
            isset($data['P_PMarzy_3_1']) => P_PMarzy_3_1Group::normalizeXmlArray($data),
            isset($data['P_PMarzy_3_2']) => P_PMarzy_3_2Group::normalizeXmlArray($data),
            default => P_PMarzy_3_3Group::normalizeXmlArray($data),
        };

        return Arr::only($data, ['P_PMarzy', 'P_PMarzy_2_3Group']);
    }
}
