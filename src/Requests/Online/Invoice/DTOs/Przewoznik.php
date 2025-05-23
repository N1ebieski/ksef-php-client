<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class Przewoznik extends AbstractDTO implements DomSerializableInterface
{
    public function __construct(
        public PrzewoznikDaneIdentyfikacyjne $daneIdentyfikacyjne,
        public AdresPrzewoznika $adresPrzewoznika,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $przewoznik = $dom->createElement('Przewoznik');
        $dom->appendChild($przewoznik);

        $daneIdentyfikacyjne = $dom->importNode($this->daneIdentyfikacyjne->toDom()->documentElement, true);

        $przewoznik->appendChild($daneIdentyfikacyjne);

        $adresPrzewoznika = $dom->importNode($this->adresPrzewoznika->toDom()->documentElement, true);

        $przewoznik->appendChild($adresPrzewoznika);

        return $dom;
    }
}
