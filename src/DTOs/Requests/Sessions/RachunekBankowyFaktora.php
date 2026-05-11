<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;
use DOMElement;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\NazwaBanku;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\OpisRachunku;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\RachunekWlasnyBanku;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;

final class RachunekBankowyFaktora extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    public function __construct(
        public readonly NrRBGroup $nrRBGroup,
        public readonly Optional | RachunekWlasnyBanku $rachunekWlasnyBanku = new Optional(),
        public readonly Optional | NazwaBanku $nazwaBanku = new Optional(),
        public readonly Optional | OpisRachunku $opisRachunku = new Optional(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $rachunekBankowyFaktora = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'RachunekBankowyFaktora');
        $dom->appendChild($rachunekBankowyFaktora);

        /** @var DOMElement $nrRBGroup */
        $nrRBGroup = $this->nrRBGroup->toDom()->documentElement;

        foreach ($nrRBGroup->childNodes as $child) {
            $rachunekBankowyFaktora->appendChild($dom->importNode($child, true));
        }

        if ($this->rachunekWlasnyBanku instanceof RachunekWlasnyBanku) {
            $rachunekWlasnyBanku = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'RachunekWlasnyBanku');
            $rachunekWlasnyBanku->appendChild($dom->createTextNode((string) $this->rachunekWlasnyBanku->value));

            $rachunekBankowyFaktora->appendChild($rachunekWlasnyBanku);
        }

        if ($this->nazwaBanku instanceof NazwaBanku) {
            $nazwaBanku = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'NazwaBanku');
            $nazwaBanku->appendChild($dom->createTextNode((string) $this->nazwaBanku));

            $rachunekBankowyFaktora->appendChild($nazwaBanku);
        }

        if ($this->opisRachunku instanceof OpisRachunku) {
            $opisRachunku = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'OpisRachunku');
            $opisRachunku->appendChild($dom->createTextNode((string) $this->opisRachunku));

            $rachunekBankowyFaktora->appendChild($opisRachunku);
        }

        return $dom;
    }

    public static function fromXmlArray(array $data): self
    {
        return new self(
            nrRBGroup: NrRBGroup::fromXmlArray($data),
            rachunekWlasnyBanku: isset($data['RachunekWlasnyBanku'])
                ? RachunekWlasnyBanku::from($data['RachunekWlasnyBanku'])
                : new Optional(),
            nazwaBanku: isset($data['NazwaBanku']) ? new NazwaBanku($data['NazwaBanku']) : new Optional(),
            opisRachunku: isset($data['OpisRachunku']) ? new OpisRachunku($data['OpisRachunku']) : new Optional(),
        );
    }
}
