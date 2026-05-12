<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\XmlNormalizableInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\TKlucz;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\TWartosc;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class TMetaDane extends AbstractDTO implements DomSerializableInterface, XmlNormalizableInterface
{
    public function __construct(
        public readonly TKlucz $tKlucz,
        public readonly TWartosc $tWartosc,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $tMetaDane = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'TMetaDane');
        $dom->appendChild($tMetaDane);

        $tKlucz = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'TKlucz');
        $tKlucz->appendChild($dom->createTextNode((string) $this->tKlucz));

        $tMetaDane->appendChild($tKlucz);

        $tWartosc = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'TWartosc');
        $tWartosc->appendChild($dom->createTextNode((string) $this->tWartosc));

        $tMetaDane->appendChild($tWartosc);

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        return Arr::only($data, ['TKlucz', 'TWartosc']);
    }
}
