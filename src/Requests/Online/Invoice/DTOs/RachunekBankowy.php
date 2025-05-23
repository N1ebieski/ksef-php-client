<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use DOMElement;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\NazwaBanku;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\OpisRachunku;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\RachunekWlasnyBanku;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;

final readonly class RachunekBankowy extends AbstractDTO implements DomSerializableInterface
{
    public function __construct(
        public NrRBGroup $nrRBGroup,
        public Optional | RachunekWlasnyBanku $rachunekWlasnyBanku = new Optional(),
        public Optional | NazwaBanku $nazwaBanku = new Optional(),
        public Optional | OpisRachunku $opisRachunku = new Optional(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $rachunekBankowy = $dom->createElement('RachunekBankowy');
        $dom->appendChild($rachunekBankowy);

        /** @var DOMElement $nrRBGroup */
        $nrRBGroup = $this->nrRBGroup->toDom()->documentElement;

        foreach ($nrRBGroup->childNodes as $child) {
            $rachunekBankowy->appendChild($dom->importNode($child, true));
        }

        if ($this->rachunekWlasnyBanku instanceof RachunekWlasnyBanku) {
            $rachunekWlasnyBanku = $dom->createElement('RachunekWlasnyBanku');
            $rachunekWlasnyBanku->appendChild($dom->createTextNode((string) $this->rachunekWlasnyBanku->value));

            $rachunekBankowy->appendChild($rachunekWlasnyBanku);
        }

        if ($this->nazwaBanku instanceof NazwaBanku) {
            $nazwaBanku = $dom->createElement('NazwaBanku');
            $nazwaBanku->appendChild($dom->createTextNode((string) $this->nazwaBanku));

            $rachunekBankowy->appendChild($nazwaBanku);
        }

        if ($this->opisRachunku instanceof OpisRachunku) {
            $opisRachunku = $dom->createElement('OpisRachunku');
            $opisRachunku->appendChild($dom->createTextNode((string) $this->opisRachunku));

            $rachunekBankowy->appendChild($opisRachunku);
        }

        return $dom;
    }
}
