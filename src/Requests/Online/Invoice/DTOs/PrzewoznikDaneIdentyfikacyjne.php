<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use DOMElement;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\Nazwa;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;

final readonly class PrzewoznikDaneIdentyfikacyjne extends AbstractDTO implements DomSerializableInterface
{
    public function __construct(
        public NIPGroup | UEGroup | KrajGroup | BrakIDGroup $idGroup,
        public Optional | Nazwa $nazwa = new Optional()
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $daneIdentyfikacyjne = $dom->createElement('DaneIdentyfikacyjne');
        $dom->appendChild($daneIdentyfikacyjne);

        /** @var DOMElement $idGroup */
        $idGroup = $this->idGroup->toDom()->documentElement;

        foreach ($idGroup->childNodes as $child) {
            $daneIdentyfikacyjne->appendChild($dom->importNode($child, true));
        }

        if ($this->nazwa instanceof Nazwa) {
            $nazwa = $dom->createElement('Nazwa');
            $nazwa->appendChild($dom->createTextNode((string) $this->nazwa));

            $daneIdentyfikacyjne->appendChild($nazwa);
        }

        return $dom;
    }
}
