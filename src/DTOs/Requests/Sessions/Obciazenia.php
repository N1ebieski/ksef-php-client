<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\XmlNormalizableInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\Kwota;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\Powod;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class Obciazenia extends AbstractDTO implements DomSerializableInterface, XmlNormalizableInterface
{
    /**
     * @param Kwota $kwota Kwota doliczona do kwoty wykazanej w polu P_15
     * @param Powod $powod Powód obciążenia
     */
    public function __construct(
        public readonly Kwota $kwota,
        public readonly Powod $powod
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $obciazenia = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Obciazenia');
        $dom->appendChild($obciazenia);

        $kwota = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Kwota');
        $kwota->appendChild($dom->createTextNode($this->kwota->value));

        $obciazenia->appendChild($kwota);

        $powod = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Powod');
        $powod->appendChild($dom->createTextNode($this->powod->value));

        $obciazenia->appendChild($powod);

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        return Arr::only($data, ['Kwota', 'Powod']);
    }
}
