<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\IDNabywcy;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\NrEORI;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\NrKlienta;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\Udzial;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;

final readonly class Podmiot3 extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param Optional|IDNabywcy $idNabywcy Unikalny klucz powiązania danych nabywcy na fakturach korygujących, w przypadku gdy dane nabywcy na fakturze korygującej zmieniły się w stosunku do danych na fakturze korygowanej
     * @param NrEORI|Optional $nrEORI Numer EORI podmiotu trzeciego
     * @param Podmiot3DaneIdentyfikacyjne $daneIdentyfikacyjne Dane identyfikujące podmiot trzeci
     * @param Adres $adres Adres podmiotu trzeciego
     * @param array<int, DaneKontaktowe> $daneKontaktowe Dane kontaktowe podmiotu trzeciego
     * @param Udzial|Optional $udzial Udział - procentowy udział dodatkowego nabywcy. Różnica pomiędzy wartością 100% a sumą udziałów dodatkowych nabywców jest udziałem nabywcy wymienionego w części Podmiot2. W przypadku niewypełnienia pola przyjmuje się, że udziały występujących na fakturze nabywców są równe
     * @param Optional|NrKlienta $nrKlienta Numer klienta dla przypadków, w których podmiot wymieniony jako podmiot trzeci posługuje się nim w umowie lub zamówieniu
     */
    public function __construct(
        public Podmiot3DaneIdentyfikacyjne $daneIdentyfikacyjne,
        public Adres $adres,
        public RolaGroup | RolaInnaGroup $rolagroup,
        public Optional | IDNabywcy $idNabywcy = new Optional(),
        public Optional | NrEORI $nrEORI = new Optional(),
        public Optional | AdresKoresp $adresKoresp = new Optional(),
        public array $daneKontaktowe = [],
        public Optional | Udzial $udzial = new Optional(),
        public Optional | NrKlienta $nrKlienta = new Optional()
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $podmiot3 = $dom->createElement('Podmiot3');
        $dom->appendChild($podmiot3);

        if ($this->idNabywcy instanceof IDNabywcy) {
            $idNabywcy = $dom->createElement('IDNabywcy');
            $idNabywcy->appendChild($dom->createTextNode((string) $this->idNabywcy));
            $podmiot3->appendChild($idNabywcy);
        }

        if ($this->nrEORI instanceof NrEORI) {
            $nrEORI = $dom->createElement('NrEORI');
            $nrEORI->appendChild($dom->createTextNode((string) $this->nrEORI));
            $podmiot3->appendChild($nrEORI);
        }

        $daneIdentyfikacyjne = $dom->importNode($this->daneIdentyfikacyjne->toDom()->documentElement, true);

        $podmiot3->appendChild($daneIdentyfikacyjne);

        $adres = $dom->importNode($this->adres->toDom()->documentElement, true);

        $podmiot3->appendChild($adres);

        if ($this->adresKoresp instanceof AdresKoresp) {
            $adresKoresp = $dom->importNode($this->adresKoresp->toDom()->documentElement, true);

            $podmiot3->appendChild($adresKoresp);
        }

        foreach ($this->daneKontaktowe as $daneKontaktowe) {
            $daneKontaktowe = $dom->importNode($daneKontaktowe->toDom()->documentElement, true);
            $podmiot3->appendChild($daneKontaktowe);
        }

        $rolagroup = $dom->importNode($this->rolagroup->toDom()->documentElement, true);

        $podmiot3->appendChild($rolagroup);

        if ($this->udzial instanceof Udzial) {
            $udzial = $dom->createElement('Udzial');
            $udzial->appendChild($dom->createTextNode((string) $this->udzial));
            $podmiot3->appendChild($udzial);
        }

        if ($this->nrKlienta instanceof NrKlienta) {
            $nrKlienta = $dom->createElement('NrKlienta');
            $nrKlienta->appendChild($dom->createTextNode((string) $this->nrKlienta));
            $podmiot3->appendChild($nrKlienta);
        }

        return $dom;
    }
}
