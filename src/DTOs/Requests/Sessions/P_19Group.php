<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use DOMElement;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\XmlNormalizableInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_19;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class P_19Group extends AbstractDTO implements DomSerializableInterface, XmlNormalizableInterface
{
    /**
     * @param P_19 $p_19 Znacznik dostawy towarów lub świadczenia usług zwolnionych od podatku na podstawie art. 43 ust. 1, art. 113 ust. 1 i 9 albo przepisów wydanych na podstawie art. 82 ust. 3 ustawy lub na podstawie innych przepisów
     */
    public function __construct(
        public readonly P_19AGroup | P_19BGroup | P_19CGroup $p_19ABCGroup,
        public readonly P_19 $p_19 = P_19::Default,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_19Group = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_19Group');
        $dom->appendChild($p_19Group);

        $p_19 = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_19');
        $p_19->appendChild($dom->createTextNode((string) $this->p_19->value));

        $p_19Group->appendChild($p_19);

        /** @var DOMElement $p_19ABCGroup */
        $p_19ABCGroup = $this->p_19ABCGroup->toDom()->documentElement;

        foreach ($p_19ABCGroup->childNodes as $child) {
            $p_19Group->appendChild($dom->importNode($child, true));
        }

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        $data['P_19ABCGroup'] = match (true) {
            isset($data['P_19A']) => P_19AGroup::normalizeXmlArray($data),
            isset($data['P_19B']) => P_19BGroup::normalizeXmlArray($data),
            default => P_19CGroup::normalizeXmlArray($data),
        };

        return Arr::only($data, ['P_19', 'P_19ABCGroup']);
    }
}
