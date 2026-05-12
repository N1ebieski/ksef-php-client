<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\Validator\Rules\Array\MaxRule;
use N1ebieski\KSEFClient\Validator\Validator;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\GV;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\IDNabywcy;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\JST;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\NrEORI;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\NrKlienta;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class Podmiot2 extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    /**
     * @var Optional|array<int, DaneKontaktowe>
     */
    public readonly Optional | array $daneKontaktowe;

    /**
     * @param Podmiot2DaneIdentyfikacyjne $daneIdentyfikacyjne Dane identyfikujące nabywcę
     * @param Adres|Optional $adres Adres nabywcy
     * @param Optional|array<int, DaneKontaktowe> $daneKontaktowe Dane kontaktowe nabywcy
     * @param Optional|NrKlienta $nrKlienta Numer klienta dla przypadków, w których nabywca posługuje się nim w umowie lub zamówieniu
     * @param NrEORI|Optional $nrEORI Numer EORI podatnika (nabywcy)
     * @param IDNabywcy|Optional $idNabywcy Unikalny klucz powiązania danych nabywcy na fakturach korygujących, w przypadku gdy dane nabywcy na fakturze korygującej zmieniły się w stosunku do danych na fakturze korygowanej
     * @param JST $jst Znacznik jednostki podrzędnej JST. Wartość "1" oznacza, że faktura dotyczy jednostki podrzędnej JST. W takim przypadku, aby udostępnić fakturę jednostce podrzędnej JST, należy wypełnić sekcję Podmiot3, w szczególności podać NIP lub ID-Wew i określić rolę jako 8. Wartość "2" oznacza, że faktura nie dotyczy jednostki podrzędnej JST
     * @param GV $gv Znacznik członka grupy VAT. Wartość "1" oznacza, że faktura dotyczy członka grupy VAT. W takim przypadku, aby udostępnić fakturę członkowi grupy VAT, należy wypełnić sekcję Podmiot3, w szczególności podać NIP lub ID-Wew i określić rolę jako 10. Wartość "2" oznacza, że faktura nie dotyczy członka grupy VAT
     */
    public function __construct(
        public readonly Podmiot2DaneIdentyfikacyjne $daneIdentyfikacyjne,
        public readonly JST $jst = JST::No,
        public readonly GV $gv = GV::No,
        public readonly Optional | NrEORI $nrEORI = new Optional(),
        public readonly Optional | Adres $adres = new Optional(),
        public readonly Optional | AdresKoresp $adresKoresp = new Optional(),
        Optional | array $daneKontaktowe = new Optional(),
        public readonly Optional | NrKlienta $nrKlienta = new Optional(),
        public readonly Optional | IDNabywcy $idNabywcy = new Optional()
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

        $podmiot2 = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Podmiot2');
        $dom->appendChild($podmiot2);

        if ($this->nrEORI instanceof NrEORI) {
            $nrEORI = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'NrEORI');
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
            $nrKlienta = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'NrKlienta');
            $nrKlienta->appendChild($dom->createTextNode((string) $this->nrKlienta));

            $podmiot2->appendChild($nrKlienta);
        }

        if ($this->idNabywcy instanceof IDNabywcy) {
            $idNabywcy = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'IDNabywcy');
            $idNabywcy->appendChild($dom->createTextNode((string) $this->idNabywcy));

            $podmiot2->appendChild($idNabywcy);
        }

        $jst = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'JST');
        $jst->appendChild($dom->createTextNode((string) $this->jst->value));

        $podmiot2->appendChild($jst);

        $gv = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'GV');
        $gv->appendChild($dom->createTextNode((string) $this->gv->value));

        $podmiot2->appendChild($gv);

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        $data['DaneIdentyfikacyjne'] = Podmiot2DaneIdentyfikacyjne::normalizeXmlArray($data['DaneIdentyfikacyjne']);

        $data['Adres'] = match (true) {
            isset($data['Adres']) => Adres::normalizeXmlArray($data['Adres']),
            default => new Optional(),
        };

        $data['AdresKoresp'] = match (true) {
            isset($data['AdresKoresp']) => AdresKoresp::normalizeXmlArray($data['AdresKoresp']),
            default => new Optional(),
        };

        $data['DaneKontaktowe'] = match (true) {
            isset($data['DaneKontaktowe']) => array_map(
                fn (array $item): array => DaneKontaktowe::normalizeXmlArray($item),
                Arr::ensureList($data['DaneKontaktowe'])
            ),
            default => new Optional(),
        };

        $data['jst'] = $data['JST'] ?? JST::No;
        $data['gv'] = $data['GV'] ?? GV::No;
        $data['idNabywcy'] = $data['IDNabywcy'] ?? new Optional();

        return Arr::onlyClassParameters($data, self::class);
    }
}
