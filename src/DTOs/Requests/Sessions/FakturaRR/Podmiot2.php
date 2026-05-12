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
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\FakturaRR\StatusInfoPodatnika;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class Podmiot2 extends AbstractDTO implements DomSerializableInterface, XmlNormalizableInterface
{
    /**
     * @var Optional|array<int, DaneKontaktowe>
     */
    public readonly Optional | array $daneKontaktowe;

    /**
     * @param Podmiot1DaneIdentyfikacyjne $daneIdentyfikacyjne Element zawierający dane identyfikujące nabywcę (podatnika VAT czynnego): jego NIP oraz imię i nazwisko lub nazwę albo nazwę skróconą.
     * @param Adres $adres Element zawierający dane dotyczące adresu nabywcy (podatnika VAT czynnego).
     * @param Optional|AdresKoresp $adresKoresp Element zawierający dane dotyczące adresu korespondencyjnego nabywcy (podatnika VAT czynnego)
     * @param Optional|array<int, DaneKontaktowe> $daneKontaktowe Element zawierający dane kontaktowe nabywcy (podatnika VAT czynnego): adres e-mail oraz numer telefonu
     * @param Optional|StatusInfoPodatnika $statusInfoPodatnika Status nabywcy (podatnika VAT czynnego)
     */
    public function __construct(
        public readonly Podmiot1DaneIdentyfikacyjne $daneIdentyfikacyjne,
        public readonly Adres $adres,
        public readonly Optional | AdresKoresp $adresKoresp = new Optional(),
        Optional | array $daneKontaktowe = new Optional(),
        public readonly Optional | StatusInfoPodatnika $statusInfoPodatnika = new Optional(),
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

        $podmiot2 = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'Podmiot2');
        $dom->appendChild($podmiot2);

        $daneIdentyfikacyjne = $dom->importNode($this->daneIdentyfikacyjne->toDom()->documentElement, true);
        $podmiot2->appendChild($daneIdentyfikacyjne);

        $adres = $dom->importNode($this->adres->toDom()->documentElement, true);
        $podmiot2->appendChild($adres);

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

        if ($this->statusInfoPodatnika instanceof StatusInfoPodatnika) {
            $statusInfoPodatnika = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'StatusInfoPodatnika');
            $statusInfoPodatnika->appendChild($dom->createTextNode((string) $this->statusInfoPodatnika->value));
            $podmiot2->appendChild($statusInfoPodatnika);
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

        return Arr::only($data, ['DaneIdentyfikacyjne', 'Adres', 'AdresKoresp', 'DaneKontaktowe', 'StatusInfoPodatnika']);
    }
}
