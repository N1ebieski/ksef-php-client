<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\ValueObjects\NIP;

final class NIPGroup extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    public function __construct(
        public readonly NIP $nip,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $nipGroup = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'NIPGroup');
        $dom->appendChild($nipGroup);

        $nip = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'NIP');
        $nip->appendChild($dom->createTextNode((string) $this->nip));

        $nipGroup->appendChild($nip);

        return $dom;
    }

    public static function fromXmlArray(array $data): self
    {
        return new self(nip: new NIP($data['nip']));
    }
}
