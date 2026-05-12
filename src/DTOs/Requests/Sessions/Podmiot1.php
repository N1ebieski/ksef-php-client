<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\XmlNormalizableInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\Validator\Rules\Array\MaxRule;
use N1ebieski\KSEFClient\Validator\Validator;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\NrEORI;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\PrefiksPodatnika;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\StatusInfoPodatnika;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class Podmiot1 extends AbstractDTO implements DomSerializableInterface, XmlNormalizableInterface
{
    /**
     * @var Optional|array<int, DaneKontaktowe>
     */
    public readonly Optional | array $daneKontaktowe;

    /**
     * @param Podmiot1DaneIdentyfikacyjne $daneIdentyfikacyjne Dane identyfikujące podatnika
     * @param Adres $adres Adres podatnika
     * @param Optional|array<int, DaneKontaktowe> $daneKontaktowe Dane kontaktowe podatnika
     * @param PrefiksPodatnika|Optional $prefiksPodatnika Kod (prefiks) podatnika VAT UE dla przypadków określonych w art. 97 ust. 10 pkt 2 i 3 ustawy oraz w przypadku, o którym mowa w art. 136 ust. 1 pkt 3 ustawy
     * @param NrEORI|Optional $nrEORI Numer EORI podatnika (sprzedawcy)
     */
    public function __construct(
        public readonly Podmiot1DaneIdentyfikacyjne $daneIdentyfikacyjne,
        public readonly Adres $adres,
        Optional | array $daneKontaktowe = new Optional(),
        public readonly Optional | PrefiksPodatnika $prefiksPodatnika = new Optional(),
        public readonly Optional | NrEORI $nrEORI = new Optional(),
        public readonly Optional | AdresKoresp $adresKoresp = new Optional(),
        public readonly Optional | StatusInfoPodatnika $statusInfoPodatnika = new Optional()
    ) {
        Validator::validate([
            'daneKontaktowe' => $daneKontaktowe
        ], [
            'daneKontaktowe' => [new MaxRule(3)]
        ]);

        $this->daneKontaktowe = $daneKontaktowe;
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $podmiot1 = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Podmiot1');
        $dom->appendChild($podmiot1);

        if ($this->prefiksPodatnika instanceof PrefiksPodatnika) {
            $prefiksPodatnika = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'PrefiksPodatnika');
            $prefiksPodatnika->appendChild($dom->createTextNode((string) $this->prefiksPodatnika));
            $podmiot1->appendChild($prefiksPodatnika);
        }

        if ($this->nrEORI instanceof NrEORI) {
            $nrEORI = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'NrEORI');
            $nrEORI->appendChild($dom->createTextNode((string) $this->nrEORI));
            $podmiot1->appendChild($nrEORI);
        }

        $daneIdentyfikacyjne = $dom->importNode($this->daneIdentyfikacyjne->toDom()->documentElement, true);

        $podmiot1->appendChild($daneIdentyfikacyjne);

        $adres = $dom->importNode($this->adres->toDom()->documentElement, true);

        $podmiot1->appendChild($adres);

        if ($this->adresKoresp instanceof AdresKoresp) {
            $adresKoresp = $dom->importNode($this->adresKoresp->toDom()->documentElement, true);
            $podmiot1->appendChild($adresKoresp);
        }

        if ( ! $this->daneKontaktowe instanceof Optional) {
            foreach ($this->daneKontaktowe as $daneKontaktowe) {
                $daneKontaktowe = $dom->importNode($daneKontaktowe->toDom()->documentElement, true);
                $podmiot1->appendChild($daneKontaktowe);
            }
        }

        if ($this->statusInfoPodatnika instanceof StatusInfoPodatnika) {
            $statusInfoPodatnika = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'StatusInfoPodatnika');
            $statusInfoPodatnika->appendChild($dom->createTextNode((string) $this->statusInfoPodatnika->value));
            $podmiot1->appendChild($statusInfoPodatnika);
        }

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        $data['DaneIdentyfikacyjne'] = Podmiot1DaneIdentyfikacyjne::normalizeXmlArray($data['DaneIdentyfikacyjne']);

        $data['Adres'] = Adres::normalizeXmlArray($data['Adres']);

        $data['DaneKontaktowe'] = match (true) {
            isset($data['DaneKontaktowe']) => array_map(
                fn (array $item): array => DaneKontaktowe::normalizeXmlArray($item),
                Arr::ensureList($data['DaneKontaktowe'])
            ),
            default => new Optional(),
        };

        $data['AdresKoresp'] = match (true) {
            isset($data['AdresKoresp']) => AdresKoresp::normalizeXmlArray($data['AdresKoresp']),
            default => new Optional(),
        };

        return Arr::only($data, ['PrefiksPodatnika', 'NrEORI', 'DaneIdentyfikacyjne', 'Adres', 'DaneKontaktowe', 'AdresKoresp', 'StatusInfoPodatnika']);
    }
}
