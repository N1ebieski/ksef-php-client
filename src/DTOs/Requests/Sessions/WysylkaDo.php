<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\XmlNormalizableInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\AdresL1;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\AdresL2;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\GLN;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\KodKraju;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class WysylkaDo extends AbstractDTO implements DomSerializableInterface, XmlNormalizableInterface
{
    /**
     * @param Optional|GLN $gln Globalny Numer Lokalizacyjny [Global Location Number]
     */
    public function __construct(
        public readonly AdresL1 $adresL1,
        public readonly KodKraju $kodKraju = new KodKraju('PL'),
        public readonly Optional | AdresL2 $adresL2 = new Optional(),
        public readonly Optional | GLN $gln = new Optional()
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $wysylkaDo = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'WysylkaDo');
        $dom->appendChild($wysylkaDo);

        $kodKraju = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'KodKraju');
        $kodKraju->appendChild($dom->createTextNode((string) $this->kodKraju));

        $wysylkaDo->appendChild($kodKraju);

        $adresL1 = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'AdresL1');
        $adresL1->appendChild($dom->createTextNode((string) $this->adresL1));

        $wysylkaDo->appendChild($adresL1);

        if ($this->adresL2 instanceof AdresL2) {
            $adresL2 = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'AdresL2');
            $adresL2->appendChild($dom->createTextNode((string) $this->adresL2));
            $wysylkaDo->appendChild($adresL2);
        }

        if ($this->gln instanceof GLN) {
            $gln = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'GLN');
            $gln->appendChild($dom->createTextNode((string) $this->gln));
            $wysylkaDo->appendChild($gln);
        }

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        return Arr::only($data, ['AdresL1', 'KodKraju', 'AdresL2', 'GLN']);
    }
}
