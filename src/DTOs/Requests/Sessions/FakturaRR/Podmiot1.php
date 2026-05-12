<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions\FakturaRR;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\XmlNormalizableInterface;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\FakturaRR\Adres;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\FakturaRR\AdresKoresp;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\FakturaRR\DaneKontaktowe;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\FakturaRR\Podmiot1DaneIdentyfikacyjne;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\Validator\Rules\Array\MaxRule;
use N1ebieski\KSEFClient\Validator\Validator;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\FakturaRR\NrKontrahenta;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class Podmiot1 extends AbstractDTO implements DomSerializableInterface, XmlNormalizableInterface
{
    /**
     * @var Optional|array<int, DaneKontaktowe>
     */
    public readonly Optional | array $daneKontaktowe;

    /**
     * @param Podmiot1DaneIdentyfikacyjne $daneIdentyfikacyjne Element zawierający dane identyfikujące dostawcę (rolnika ryczałtowego): jego NIP oraz imię i nazwisko lub nazwę albo nazwę skróconą.
     * @param Adres $adres Element zawierający dane dotyczące adresu dostawcy (rolnika ryczałtowego).
     * @param Optional|AdresKoresp $adresKoresp Element zawierający dane dotyczące adresu korespondencyjnego dostawcy (rolnika ryczałtowego)
     * @param Optional|array<int, DaneKontaktowe> $daneKontaktowe Element zawierający dane kontaktowe dostawcy (rolnika ryczałtowego): adres e-mail oraz numer telefonu
     * @param Optional|NrKontrahenta $nrKontrahenta Numer kontrahenta dla przypadków, w których dostawca posługuje się nim w umowie lub zamówieniu
     */
    public function __construct(
        public readonly Podmiot1DaneIdentyfikacyjne $daneIdentyfikacyjne,
        public readonly Adres $adres,
        public readonly Optional | AdresKoresp $adresKoresp = new Optional(),
        Optional | array $daneKontaktowe = new Optional(),
        public readonly Optional | NrKontrahenta $nrKontrahenta = new Optional(),
    ) {
        Validator::validate([
            'daneKontaktowe' => $daneKontaktowe,
        ], [
            'daneKontaktowe' => [new MaxRule(3)],
        ]);

        $this->daneKontaktowe = $daneKontaktowe;
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $podmiot1 = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'Podmiot1');
        $dom->appendChild($podmiot1);

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

        if ($this->nrKontrahenta instanceof NrKontrahenta) {
            $nrKontrahenta = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'NrKontrahenta');
            $nrKontrahenta->appendChild($dom->createTextNode((string) $this->nrKontrahenta));
            $podmiot1->appendChild($nrKontrahenta);
        }

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        $data['DaneIdentyfikacyjne'] = Podmiot1DaneIdentyfikacyjne::normalizeXmlArray($data['DaneIdentyfikacyjne']);

        $data['Adres'] = Adres::normalizeXmlArray($data['Adres']);

        $data['AdresKoresp'] = match (true) {
            isset($data['AdresKoresp']) => AdresKoresp::normalizeXmlArray($data['AdresKoresp']),
            default => new Optional(),
        };

        $data['DaneKontaktowe'] = match (true) {
            isset($data['DaneKontaktowe']) => array_map(
                DaneKontaktowe::normalizeXmlArray(...), //@phpstan-ignore-line argument.type
                Arr::ensureList($data['DaneKontaktowe'])
            ),
            default => new Optional(),
        };

        return Arr::only($data, ['DaneIdentyfikacyjne', 'Adres', 'AdresKoresp', 'DaneKontaktowe', 'NrKontrahenta']);
    }
}
