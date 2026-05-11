<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\ZKlucz;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\ZWartosc;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class MetaDane extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    public function __construct(
        public readonly ZKlucz $zKlucz,
        public readonly ZWartosc $zWartosc,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $metaDane = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'MetaDane');
        $dom->appendChild($metaDane);

        $zKlucz = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'ZKlucz');
        $zKlucz->appendChild($dom->createTextNode((string) $this->zKlucz));

        $metaDane->appendChild($zKlucz);

        $zWartosc = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'ZWartosc');
        $zWartosc->appendChild($dom->createTextNode((string) $this->zWartosc));

        $metaDane->appendChild($zWartosc);

        return $dom;
    }

    public static function fromXmlArray(array $data): self
    {
        return new self(
            zKlucz: new ZKlucz($data['ZKlucz']),
            zWartosc: new ZWartosc($data['ZWartosc']),
        );
    }
}
