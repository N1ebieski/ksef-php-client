<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\Kwota;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\Powod;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final class Obciazenia extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
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

    public static function fromXmlArray(array $data): self
    {
        return new self(
            kwota: new Kwota($data['kwota']),
            powod: new Powod($data['powod']),
        );
    }
}
