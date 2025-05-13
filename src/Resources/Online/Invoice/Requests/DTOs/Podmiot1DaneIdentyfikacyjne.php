<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\Nazwa;
use N1ebieski\KSEFClient\Resources\Online\ValueObjects\XmlNamespace;
use N1ebieski\KSEFClient\Support\DTO;
use N1ebieski\KSEFClient\ValueObjects\NIP;

final readonly class Podmiot1DaneIdentyfikacyjne extends DTO implements DomSerializableInterface
{
    public function __construct(
        public NIP $nip,
        public Nazwa $nazwa
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $daneIdentyfikacyjne = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'DaneIdentyfikacyjne');
        $dom->appendChild($daneIdentyfikacyjne);

        $nip = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'NIP');
        $nip->appendChild($dom->createTextNode((string) $this->nip));

        $daneIdentyfikacyjne->appendChild($nip);

        $nazwa = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'Nazwa');
        $nazwa->appendChild($dom->createTextNode((string) $this->nazwa));

        $daneIdentyfikacyjne->appendChild($nazwa);

        return $dom;
    }
}
