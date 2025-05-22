<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\PrefiksPodatnika;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;

final readonly class Podmiot1K extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param Podmiot1KDaneIdentyfikacyjne $daneIdentyfikacyjne Dane identyfikujące podatnika
     * @param Adres $adres Adres podatnika
     * @param PrefiksPodatnika|Optional $prefiksPodatnika Kod (prefiks) podatnika VAT UE dla przypadków określonych w art. 97 ust. 10 pkt 2 i 3 ustawy oraz w przypadku, o którym mowa w art. 136 ust. 1 pkt 3 ustawy
     * @return void
     */
    public function __construct(
        public Podmiot1KDaneIdentyfikacyjne $daneIdentyfikacyjne,
        public Adres $adres,
        public Optional | PrefiksPodatnika $prefiksPodatnika = new Optional(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $podmiot1K = $dom->createElement('Podmiot1K');
        $dom->appendChild($podmiot1K);

        if ($this->prefiksPodatnika instanceof PrefiksPodatnika) {
            $prefiksPodatnika = $dom->createElement('PrefiksPodatnika');
            $prefiksPodatnika->appendChild($dom->createTextNode((string) $this->prefiksPodatnika));
            $podmiot1K->appendChild($prefiksPodatnika);
        }

        $daneIdentyfikacyjne = $dom->importNode($this->daneIdentyfikacyjne->toDom()->documentElement, true);

        $podmiot1K->appendChild($daneIdentyfikacyjne);

        $adres = $dom->importNode($this->adres->toDom()->documentElement, true);

        $podmiot1K->appendChild($adres);

        return $dom;
    }
}
