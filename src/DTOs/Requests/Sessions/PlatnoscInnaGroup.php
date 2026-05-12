<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\XmlNormalizableInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\OpisPlatnosci;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\PlatnoscInna;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class PlatnoscInnaGroup extends AbstractDTO implements DomSerializableInterface, XmlNormalizableInterface
{
    /**
     * @param PlatnoscInna $platnoscInna Znacznik innej formy płatności: 1 - inna forma płatności
     * @param OpisPlatnosci $opisPlatnosci Opis płatnosci Doprecyzowanie innej formy płatności
     */
    public function __construct(
        public readonly OpisPlatnosci $opisPlatnosci,
        public readonly PlatnoscInna $platnoscInna = PlatnoscInna::Default,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $platnoscInnaGroup = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'PlatnoscInnaGroup');
        $dom->appendChild($platnoscInnaGroup);

        $platnoscInna = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'PlatnoscInna');
        $platnoscInna->appendChild($dom->createTextNode((string) $this->platnoscInna->value));

        $platnoscInnaGroup->appendChild($platnoscInna);

        $opisPlatnosci = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'OpisPlatnosci');
        $opisPlatnosci->appendChild($dom->createTextNode((string) $this->opisPlatnosci));

        $platnoscInnaGroup->appendChild($opisPlatnosci);

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        return Arr::only($data, ['PlatnoscInna', 'OpisPlatnosci']);
    }
}
