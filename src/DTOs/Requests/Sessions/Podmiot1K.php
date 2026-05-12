<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\XmlNormalizableInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\PrefiksPodatnika;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class Podmiot1K extends AbstractDTO implements DomSerializableInterface, XmlNormalizableInterface
{
    /**
     * @param Podmiot1KDaneIdentyfikacyjne $daneIdentyfikacyjne Dane identyfikujące podatnika
     * @param Adres $adres Adres podatnika
     * @param PrefiksPodatnika|Optional $prefiksPodatnika Kod (prefiks) podatnika VAT UE dla przypadków określonych w art. 97 ust. 10 pkt 2 i 3 ustawy oraz w przypadku, o którym mowa w art. 136 ust. 1 pkt 3 ustawy
     */
    public function __construct(
        public readonly Podmiot1KDaneIdentyfikacyjne $daneIdentyfikacyjne,
        public readonly Adres $adres,
        public readonly Optional | PrefiksPodatnika $prefiksPodatnika = new Optional(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $podmiot1K = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Podmiot1K');
        $dom->appendChild($podmiot1K);

        if ($this->prefiksPodatnika instanceof PrefiksPodatnika) {
            $prefiksPodatnika = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'PrefiksPodatnika');
            $prefiksPodatnika->appendChild($dom->createTextNode((string) $this->prefiksPodatnika));
            $podmiot1K->appendChild($prefiksPodatnika);
        }

        $daneIdentyfikacyjne = $dom->importNode($this->daneIdentyfikacyjne->toDom()->documentElement, true);

        $podmiot1K->appendChild($daneIdentyfikacyjne);

        $adres = $dom->importNode($this->adres->toDom()->documentElement, true);

        $podmiot1K->appendChild($adres);

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        $data['DaneIdentyfikacyjne'] = Podmiot1KDaneIdentyfikacyjne::normalizeXmlArray($data['DaneIdentyfikacyjne']);

        $data['Adres'] = Adres::normalizeXmlArray($data['Adres']);

        return Arr::only($data, ['PrefiksPodatnika', 'DaneIdentyfikacyjne', 'Adres']);
    }
}
