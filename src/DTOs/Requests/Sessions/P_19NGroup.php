<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\XmlNormalizableInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_19N;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class P_19NGroup extends AbstractDTO implements DomSerializableInterface, XmlNormalizableInterface
{
    /**
     * @param P_19N $p_19N Znacznik braku dostawy towarów lub świadczenia usług zwolnionych od podatku na podstawie art. 43 ust. 1, art. 113 ust. 1 i 9 ustawy albo przepisów wydanych na podstawie art. 82 ust. 3 ustawy lub na podstawie innych przepisów
     */
    public function __construct(
        public readonly P_19N $p_19N = P_19N::Default,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_19NGroup = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_19NGroup');
        $dom->appendChild($p_19NGroup);

        $p_19N = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_19N');
        $p_19N->appendChild($dom->createTextNode((string) $this->p_19N->value));

        $p_19NGroup->appendChild($p_19N);

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        return Arr::only($data, ['P_19N']);
    }
}
