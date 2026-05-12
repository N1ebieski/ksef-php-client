<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use DOMElement;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\Validator\Rules\Array\MaxRule;
use N1ebieski\KSEFClient\Validator\Validator;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\IDNabywcy;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\NrEORI;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\NrKlienta;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\Udzial;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class Podmiot3 extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    /**
     * @var Optional|array<int, DaneKontaktowe>
     */
    public readonly Optional | array $daneKontaktowe;

    /**
     * @param Optional|IDNabywcy $idNabywcy Unikalny klucz powiązania danych nabywcy na fakturach korygujących, w przypadku gdy dane nabywcy na fakturze korygującej zmieniły się w stosunku do danych na fakturze korygowanej
     * @param NrEORI|Optional $nrEORI Numer EORI podmiotu trzeciego
     * @param Podmiot3DaneIdentyfikacyjne $daneIdentyfikacyjne Dane identyfikujące podmiot trzeci
     * @param Optional|Adres $adres Adres podmiotu trzeciego
     * @param Optional|array<int, DaneKontaktowe> $daneKontaktowe Dane kontaktowe podmiotu trzeciego
     * @param Udzial|Optional $udzial Udział - procentowy udział dodatkowego nabywcy. Różnica pomiędzy wartością 100% a sumą udziałów dodatkowych nabywców jest udziałem nabywcy wymienionego w części Podmiot2. W przypadku niewypełnienia pola przyjmuje się, że udziały występujących na fakturze nabywców są równe
     * @param Optional|NrKlienta $nrKlienta Numer klienta dla przypadków, w których podmiot wymieniony jako podmiot trzeci posługuje się nim w umowie lub zamówieniu
     */
    public function __construct(
        public readonly Podmiot3DaneIdentyfikacyjne $daneIdentyfikacyjne,
        public readonly RolaGroup | RolaInnaGroup $rolaGroup,
        public readonly Optional | Adres $adres = new Optional(),
        public readonly Optional | IDNabywcy $idNabywcy = new Optional(),
        public readonly Optional | NrEORI $nrEORI = new Optional(),
        public readonly Optional | AdresKoresp $adresKoresp = new Optional(),
        Optional | array $daneKontaktowe = new Optional(),
        public readonly Optional | Udzial $udzial = new Optional(),
        public readonly Optional | NrKlienta $nrKlienta = new Optional()
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

        $podmiot3 = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Podmiot3');
        $dom->appendChild($podmiot3);

        if ($this->idNabywcy instanceof IDNabywcy) {
            $idNabywcy = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'IDNabywcy');
            $idNabywcy->appendChild($dom->createTextNode((string) $this->idNabywcy));
            $podmiot3->appendChild($idNabywcy);
        }

        if ($this->nrEORI instanceof NrEORI) {
            $nrEORI = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'NrEORI');
            $nrEORI->appendChild($dom->createTextNode((string) $this->nrEORI));
            $podmiot3->appendChild($nrEORI);
        }

        $daneIdentyfikacyjne = $dom->importNode($this->daneIdentyfikacyjne->toDom()->documentElement, true);

        $podmiot3->appendChild($daneIdentyfikacyjne);

        if ($this->adres instanceof Adres) {
            $adres = $dom->importNode($this->adres->toDom()->documentElement, true);

            $podmiot3->appendChild($adres);
        }

        if ($this->adresKoresp instanceof AdresKoresp) {
            $adresKoresp = $dom->importNode($this->adresKoresp->toDom()->documentElement, true);

            $podmiot3->appendChild($adresKoresp);
        }

        if ( ! $this->daneKontaktowe instanceof Optional) {
            foreach ($this->daneKontaktowe as $daneKontaktowe) {
                $daneKontaktowe = $dom->importNode($daneKontaktowe->toDom()->documentElement, true);
                $podmiot3->appendChild($daneKontaktowe);
            }
        }

        /** @var DOMElement $rolaGroup */
        $rolaGroup = $this->rolaGroup->toDom()->documentElement;

        foreach ($rolaGroup->childNodes as $child) {
            $podmiot3->appendChild($dom->importNode($child, true));
        }

        if ($this->udzial instanceof Udzial) {
            $udzial = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Udzial');
            $udzial->appendChild($dom->createTextNode((string) $this->udzial));
            $podmiot3->appendChild($udzial);
        }

        if ($this->nrKlienta instanceof NrKlienta) {
            $nrKlienta = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'NrKlienta');
            $nrKlienta->appendChild($dom->createTextNode((string) $this->nrKlienta));
            $podmiot3->appendChild($nrKlienta);
        }

        return $dom;
    }

    public static function fromXmlArray(array $data): self
    {
        throw new \LogicException('Method not implemented yet.');
    }
}
