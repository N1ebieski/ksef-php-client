<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\AdresL1;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\AdresL2;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\GLN;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\KodKraju;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;

final class AdresPrzewoznika extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
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

        $adresPrzewoznika = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'AdresPrzewoznika');
        $dom->appendChild($adresPrzewoznika);

        $kodKraju = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'KodKraju');
        $kodKraju->appendChild($dom->createTextNode((string) $this->kodKraju));

        $adresPrzewoznika->appendChild($kodKraju);

        $adresL1 = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'AdresL1');
        $adresL1->appendChild($dom->createTextNode((string) $this->adresL1));

        $adresPrzewoznika->appendChild($adresL1);

        if ($this->adresL2 instanceof AdresL2) {
            $adresL2 = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'AdresL2');
            $adresL2->appendChild($dom->createTextNode((string) $this->adresL2));
            $adresPrzewoznika->appendChild($adresL2);
        }

        if ($this->gln instanceof GLN) {
            $gln = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'GLN');
            $gln->appendChild($dom->createTextNode((string) $this->gln));
            $adresPrzewoznika->appendChild($gln);
        }

        return $dom;
    }

    public static function fromXmlArray(array $data): self
    {
        return new self(
            adresL1: new AdresL1($data['AdresL1']),
            kodKraju: new KodKraju($data['KodKraju'] ?? 'PL'),
            adresL2: isset($data['AdresL2']) ? new AdresL2($data['AdresL2']) : new Optional(),
            gln: isset($data['GLN']) ? new GLN($data['GLN']) : new Optional(),
        );
    }
}
