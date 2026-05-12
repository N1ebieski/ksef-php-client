<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use DOMElement;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\XmlNormalizableInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\NazwaBanku;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\OpisRachunku;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\RachunekWlasnyBanku;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class RachunekBankowy extends AbstractDTO implements DomSerializableInterface, XmlNormalizableInterface
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

        $rachunekBankowy = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'RachunekBankowy');
        $dom->appendChild($rachunekBankowy);

        /** @var DOMElement $nrRBGroup */
        $nrRBGroup = $this->nrRBGroup->toDom()->documentElement;

        foreach ($nrRBGroup->childNodes as $child) {
            $rachunekBankowy->appendChild($dom->importNode($child, true));
        }

        if ($this->rachunekWlasnyBanku instanceof RachunekWlasnyBanku) {
            $rachunekWlasnyBanku = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'RachunekWlasnyBanku');
            $rachunekWlasnyBanku->appendChild($dom->createTextNode((string) $this->rachunekWlasnyBanku->value));

            $rachunekBankowy->appendChild($rachunekWlasnyBanku);
        }

        if ($this->nazwaBanku instanceof NazwaBanku) {
            $nazwaBanku = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'NazwaBanku');
            $nazwaBanku->appendChild($dom->createTextNode((string) $this->nazwaBanku));

            $rachunekBankowy->appendChild($nazwaBanku);
        }

        if ($this->opisRachunku instanceof OpisRachunku) {
            $opisRachunku = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'OpisRachunku');
            $opisRachunku->appendChild($dom->createTextNode((string) $this->opisRachunku));

            $rachunekBankowy->appendChild($opisRachunku);
        }

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        $data['NrRBGroup'] = NrRBGroup::normalizeXmlArray($data);

        return Arr::only($data, ['NrRBGroup', 'RachunekWlasnyBanku', 'NazwaBanku', 'OpisRachunku']);
    }
}
