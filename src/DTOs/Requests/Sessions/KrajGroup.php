<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\XmlNormalizableInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\KodKraju;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\NrID;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class KrajGroup extends AbstractDTO implements DomSerializableInterface, XmlNormalizableInterface
{
    /**
     * @param NrID $nrID Dane identyfikujące nabywcę
     * @param Optional|KodKraju $kodKraju Kod (prefiks) nabywcy VAT UE, o którym mowa w art. 106e ust. 1 pkt 24 ustawy oraz w przypadku, o którym mowa w art. 136 ust. 1 pkt 4 ustawy
     */
    public function __construct(
        public readonly NrID $nrID,
        public readonly Optional | KodKraju $kodKraju = new Optional()
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $krajGroup = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'KrajGroup');
        $dom->appendChild($krajGroup);

        if ($this->kodKraju instanceof KodKraju) {
            $kodKraju = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'KodKraju');
            $kodKraju->appendChild($dom->createTextNode((string) $this->kodKraju));
            $krajGroup->appendChild($kodKraju);
        }

        $nrID = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'NrID');
        $nrID->appendChild($dom->createTextNode((string) $this->nrID));

        $krajGroup->appendChild($nrID);

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        return Arr::only($data, ['NrID', 'KodKraju']);
    }
}
