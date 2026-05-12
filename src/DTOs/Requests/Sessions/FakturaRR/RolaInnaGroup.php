<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions\FakturaRR;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\XmlNormalizableInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\OpisRoli;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\RolaInna;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class RolaInnaGroup extends AbstractDTO implements DomSerializableInterface, XmlNormalizableInterface
{
    /**
     * @param RolaInna $rolaInna Znacznik innego podmiotu: 1 - Inny podmiot
     * @param OpisRoli $opisRoli Opis roli podmiotu - w przypadku wyboru roli jako Inny podmiot
     */
    public function __construct(
        public readonly RolaInna $rolaInna,
        public readonly OpisRoli $opisRoli,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $rolaInnaGroup = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'RolaInnaGroup');
        $dom->appendChild($rolaInnaGroup);

        $rolaInna = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'RolaInna');
        $rolaInna->appendChild($dom->createTextNode((string) $this->rolaInna->value));

        $rolaInnaGroup->appendChild($rolaInna);

        $opisRoli = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'OpisRoli');
        $opisRoli->appendChild($dom->createTextNode((string) $this->opisRoli));

        $rolaInnaGroup->appendChild($opisRoli);

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        return Arr::only($data, ['RolaInna', 'OpisRoli']);
    }
}
