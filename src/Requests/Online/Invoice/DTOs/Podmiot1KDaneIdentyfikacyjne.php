<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\Nazwa;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\ValueObjects\NIP;

final readonly class Podmiot1KDaneIdentyfikacyjne extends AbstractDTO implements DomSerializableInterface
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

        $daneIdentyfikacyjne = $dom->createElement('DaneIdentyfikacyjne');
        $dom->appendChild($daneIdentyfikacyjne);

        $nip = $dom->createElement('NIP');
        $nip->appendChild($dom->createTextNode((string) $this->nip));

        $daneIdentyfikacyjne->appendChild($nip);

        $nazwa = $dom->createElement('Nazwa');
        $nazwa->appendChild($dom->createTextNode((string) $this->nazwa));

        $daneIdentyfikacyjne->appendChild($nazwa);

        return $dom;
    }
}
