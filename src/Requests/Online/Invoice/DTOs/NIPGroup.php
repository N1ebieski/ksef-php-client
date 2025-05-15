<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\ValueObjects\XmlNamespace;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\ValueObjects\NIP;

final readonly class NIPGroup extends AbstractDTO implements DomSerializableInterface
{
    public function __construct(
        public NIP $nip,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $nipGroup = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'NIPGroup');
        $dom->appendChild($nipGroup);

        $nip = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'NIP');
        $nip->appendChild($dom->createTextNode((string) $this->nip));

        $nipGroup->appendChild($nip);

        return $dom;
    }
}
