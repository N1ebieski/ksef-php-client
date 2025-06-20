<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\IDNabywcy;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\NrEORI;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\NrKlienta;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\Validator\Rules\Array\MaxRule;
use N1ebieski\KSEFClient\Validator\Validator;

final readonly class Podmiot2 extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @var Optional|array<int, DaneKontaktowe>
     */
    public Optional | array $daneKontaktowe;

    /**
     * @param Podmiot2DaneIdentyfikacyjne $daneIdentyfikacyjne Dane identyfikujące nabywcę
     * @param Adres|Optional $adres Adres nabywcy
     * @param Optional|array<int, DaneKontaktowe> $daneKontaktowe Dane kontaktowe nabywcy
     * @param Optional|NrKlienta $nrKlienta Numer klienta dla przypadków, w których nabywca posługuje się nim w umowie lub zamówieniu
     * @param NrEORI|Optional $nrEORI Numer EORI podatnika (nabywcy)
     * @param IDNabywcy|Optional $idNabywcy Unikalny klucz powiązania danych nabywcy na fakturach korygujących, w przypadku gdy dane nabywcy na fakturze korygującej zmieniły się w stosunku do danych na fakturze korygowanej
     * @return void
     */
    public function __construct(
        public Podmiot2DaneIdentyfikacyjne $daneIdentyfikacyjne,
        public Optional | NrEORI $nrEORI = new Optional(),
        public Optional | Adres $adres = new Optional(),
        public Optional | AdresKoresp $adresKoresp = new Optional(),
        Optional | array $daneKontaktowe = new Optional(),
        public Optional | NrKlienta $nrKlienta = new Optional(),
        public Optional | IDNabywcy $idNabywcy = new Optional()
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

        $podmiot2 = $dom->createElement('Podmiot2');
        $dom->appendChild($podmiot2);

        if ($this->nrEORI instanceof NrEORI) {
            $nrEORI = $dom->createElement('NrEORI');
            $nrEORI->appendChild($dom->createTextNode((string) $this->nrEORI));

            $podmiot2->appendChild($nrEORI);
        }

        $daneIdentyfikacyjne = $dom->importNode($this->daneIdentyfikacyjne->toDom()->documentElement, true);

        $podmiot2->appendChild($daneIdentyfikacyjne);

        if ($this->adres instanceof Adres) {
            $adres = $dom->importNode($this->adres->toDom()->documentElement, true);

            $podmiot2->appendChild($adres);
        }

        if ($this->adresKoresp instanceof AdresKoresp) {
            $adresKoresp = $dom->importNode($this->adresKoresp->toDom()->documentElement, true);

            $podmiot2->appendChild($adresKoresp);
        }

        if ( ! $this->daneKontaktowe instanceof Optional) {
            foreach ($this->daneKontaktowe as $daneKontaktowe) {
                $daneKontaktowe = $dom->importNode($daneKontaktowe->toDom()->documentElement, true);
                $podmiot2->appendChild($daneKontaktowe);
            }
        }

        if ($this->nrKlienta instanceof NrKlienta) {
            $nrKlienta = $dom->createElement('NrKlienta');
            $nrKlienta->appendChild($dom->createTextNode((string) $this->nrKlienta));

            $podmiot2->appendChild($nrKlienta);
        }

        if ($this->idNabywcy instanceof IDNabywcy) {
            $idNabywcy = $dom->createElement('IDNabywcy');
            $idNabywcy->appendChild($dom->createTextNode((string) $this->idNabywcy));

            $podmiot2->appendChild($idNabywcy);
        }

        return $dom;
    }
}
