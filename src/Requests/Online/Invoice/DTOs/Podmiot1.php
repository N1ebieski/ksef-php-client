<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\NrEORI;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\PrefiksPodatnika;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\StatusInfoPodatnika;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\Validator\Rules\Array\MaxRule;
use N1ebieski\KSEFClient\Validator\Validator;

final readonly class Podmiot1 extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @var Optional|array<int, DaneKontaktowe>
     */
    public Optional | array $daneKontaktowe;

    /**
     * @param Podmiot1DaneIdentyfikacyjne $daneIdentyfikacyjne Dane identyfikujące podatnika
     * @param Adres $adres Adres podatnika
     * @param Optional|array<int, DaneKontaktowe> $daneKontaktowe Dane kontaktowe podatnika
     * @param PrefiksPodatnika|Optional $prefiksPodatnika Kod (prefiks) podatnika VAT UE dla przypadków określonych w art. 97 ust. 10 pkt 2 i 3 ustawy oraz w przypadku, o którym mowa w art. 136 ust. 1 pkt 3 ustawy
     * @param NrEORI|Optional $nrEORI Numer EORI podatnika (sprzedawcy)
     * @return void
     */
    public function __construct(
        public Podmiot1DaneIdentyfikacyjne $daneIdentyfikacyjne,
        public Adres $adres,
        Optional | array $daneKontaktowe = new Optional(),
        public Optional | PrefiksPodatnika $prefiksPodatnika = new Optional(),
        public Optional | NrEORI $nrEORI = new Optional(),
        public Optional | AdresKoresp $adresKoresp = new Optional(),
        public Optional | StatusInfoPodatnika $statusInfoPodatnika = new Optional()
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

        $podmiot1 = $dom->createElement('Podmiot1');
        $dom->appendChild($podmiot1);

        if ($this->prefiksPodatnika instanceof PrefiksPodatnika) {
            $prefiksPodatnika = $dom->createElement('PrefiksPodatnika');
            $prefiksPodatnika->appendChild($dom->createTextNode((string) $this->prefiksPodatnika));
            $podmiot1->appendChild($prefiksPodatnika);
        }

        if ($this->nrEORI instanceof NrEORI) {
            $nrEORI = $dom->createElement('NrEORI');
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
            $statusInfoPodatnika = $dom->createElement('StatusInfoPodatnika');
            $statusInfoPodatnika->appendChild($dom->createTextNode((string) $this->statusInfoPodatnika->value));
            $podmiot1->appendChild($statusInfoPodatnika);
        }

        return $dom;
    }
}
