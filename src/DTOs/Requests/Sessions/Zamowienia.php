<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\XmlNormalizableInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\DataZamowienia;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\NrZamowienia;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class Zamowienia extends AbstractDTO implements DomSerializableInterface, XmlNormalizableInterface
{
    public function __construct(
        public readonly Optional | DataZamowienia $dataZamowienia = new Optional(),
        public readonly Optional | NrZamowienia $nrZamowienia = new Optional(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $zamowienia = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Zamowienia');
        $dom->appendChild($zamowienia);

        if ($this->dataZamowienia instanceof DataZamowienia) {
            $dataZamowienia = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'DataZamowienia');
            $dataZamowienia->appendChild($dom->createTextNode((string) $this->dataZamowienia));

            $zamowienia->appendChild($dataZamowienia);
        }

        if ($this->nrZamowienia instanceof NrZamowienia) {
            $nrZamowienia = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'NrZamowienia');
            $nrZamowienia->appendChild($dom->createTextNode((string) $this->nrZamowienia));

            $zamowienia->appendChild($nrZamowienia);
        }

        $dom->appendChild($zamowienia);

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        return Arr::only($data, ['DataZamowienia', 'NrZamowienia']);
    }
}
