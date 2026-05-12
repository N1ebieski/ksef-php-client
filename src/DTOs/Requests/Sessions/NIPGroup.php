<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\XmlNormalizableInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\ValueObjects\NIP;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class NIPGroup extends AbstractDTO implements DomSerializableInterface, XmlNormalizableInterface
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

    public static function normalizeXmlArray(array $data): array
    {
        $data['nip'] = $data['NIP'];

        return Arr::only($data, ['nip']);
    }
}
