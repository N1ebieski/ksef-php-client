<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\DataZaplatyCzesciowej;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\KwotaZaplatyCzesciowej;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class ZaplataCzesciowa extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param KwotaZaplatyCzesciowej $kwotaZaplatyCzesciowej
     * @param DataZaplatyCzesciowej $dataZaplatyCzesciowej Data zapłaty częściowej, jeśli do wystawienia faktury płatność częściowa została dokonana
     */
    public function __construct(
        public KwotaZaplatyCzesciowej $kwotaZaplatyCzesciowej,
        public DataZaplatyCzesciowej $dataZaplatyCzesciowej
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $zaplataCzesciowa = $dom->createElement('ZaplataCzesciowa');
        $dom->appendChild($zaplataCzesciowa);

        $kwotaZaplatyCzesciowej = $dom->createElement('KwotaZaplatyCzesciowej');
        $kwotaZaplatyCzesciowej->appendChild($dom->createTextNode((string) $this->kwotaZaplatyCzesciowej));

        $zaplataCzesciowa->appendChild($kwotaZaplatyCzesciowej);

        $dataZaplatyCzesciowej = $dom->createElement('DataZaplatyCzesciowej');
        $dataZaplatyCzesciowej->appendChild($dom->createTextNode((string) $this->dataZaplatyCzesciowej));

        $zaplataCzesciowa->appendChild($dataZaplatyCzesciowej);

        return $dom;
    }
}
