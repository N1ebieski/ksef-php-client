<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\Kwota;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\Powod;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class Odliczenia extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param Kwota $kwota Kwota doliczona do kwoty wykazanej w polu P_15
     * @param Powod $powod Powód obciążenia
     */
    public function __construct(
        public Kwota $kwota,
        public Powod $powod
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $odliczenia = $dom->createElement('Odliczenia');
        $dom->appendChild($odliczenia);

        $kwota = $dom->createElement('Kwota');
        $kwota->appendChild($dom->createTextNode((string) $this->kwota->value));

        $odliczenia->appendChild($kwota);

        $powod = $dom->createElement('Powod');
        $powod->appendChild($dom->createTextNode($this->powod->value));

        $odliczenia->appendChild($powod);

        return $dom;
    }
}
