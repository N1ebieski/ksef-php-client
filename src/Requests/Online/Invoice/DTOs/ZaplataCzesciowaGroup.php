<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\ZnacznikZaplatyCzesciowej;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class ZaplataCzesciowaGroup extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param array<int, ZaplataCzesciowa> $zaplataCzesciowa Dane zapłat częściowych
     * @param ZnacznikZaplatyCzesciowej $znacznikZaplatyCzesciowej Znacznik informujący, że kwota należności wynikająca z faktury została zapłacona w części: 1 - zapłacono w części
     */
    public function __construct(
        public array $zaplataCzesciowa = [],
        public ZnacznikZaplatyCzesciowej $znacznikZaplatyCzesciowej = ZnacznikZaplatyCzesciowej::Default
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $zaplataCzesciowaGroup = $dom->createElement('ZaplataCzesciowaGroup');
        $dom->appendChild($zaplataCzesciowaGroup);

        $znacznikZaplatyCzesciowej = $dom->createElement('ZnacznikZaplatyCzesciowej');
        $znacznikZaplatyCzesciowej->appendChild($dom->createTextNode((string) $this->znacznikZaplatyCzesciowej->value));

        $zaplataCzesciowaGroup->appendChild($znacznikZaplatyCzesciowej);

        foreach ($this->zaplataCzesciowa as $zaplataCzesciowa) {
            $zaplataCzesciowa = $dom->importNode($zaplataCzesciowa->toDom()->documentElement, true);

            $zaplataCzesciowaGroup->appendChild($zaplataCzesciowa);
        }

        return $dom;
    }
}
