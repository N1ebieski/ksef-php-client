<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\Rola;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class RolaGroup extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    public function __construct(
        public readonly Rola $rola,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $rolaGroup = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'RolaGroup');
        $dom->appendChild($rolaGroup);

        $rola = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Rola');
        $rola->appendChild($dom->createTextNode((string) $this->rola->value));

        $rolaGroup->appendChild($rola);

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        return Arr::onlyClassParameters($data, self::class);
    }
}
