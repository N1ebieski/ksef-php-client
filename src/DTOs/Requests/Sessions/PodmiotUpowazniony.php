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
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\RolaPU;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class PodmiotUpowazniony extends AbstractDTO implements DomSerializableInterface, XmlNormalizableInterface
{
    /**
     * @var Optional|array<int, PodmiotUpowaznionyDaneKontaktowe>
     */
    public readonly Optional | array $daneKontaktowe;

    /**
     * @param PodmiotUpowaznionyDaneIdentyfikacyjne $daneIdentyfikacyjne Dane identyfikujące podmiotu upoważnionego
     * @param Adres $adres Adres podmiotu upoważnionego
     * @param NrEORI|Optional $nrEORI Numer EORI podmiotu upoważnionego
     * @param Optional|array<int, PodmiotUpowaznionyDaneKontaktowe> $daneKontaktowe Dane kontaktowe podmiotu upoważnionego
     * @param RolaPU $rolaPU Rola podmiotu upoważnionego
     */
    public function __construct(
        public readonly PodmiotUpowaznionyDaneIdentyfikacyjne $daneIdentyfikacyjne,
        public readonly Adres $adres,
        public readonly RolaPU $rolaPU,
        public readonly Optional | NrEORI $nrEORI = new Optional(),
        public readonly Optional | AdresKoresp $adresKoresp = new Optional(),
        Optional | array $daneKontaktowe = new Optional(),
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

        $podmiotUpowazniony = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'PodmiotUpowazniony');
        $dom->appendChild($podmiotUpowazniony);

        if ($this->nrEORI instanceof NrEORI) {
            $nrEORI = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'NrEORI');
            $nrEORI->appendChild($dom->createTextNode((string) $this->nrEORI));
            $podmiotUpowazniony->appendChild($nrEORI);
        }

        $daneIdentyfikacyjne = $dom->importNode($this->daneIdentyfikacyjne->toDom()->documentElement, true);

        $podmiotUpowazniony->appendChild($daneIdentyfikacyjne);

        $adres = $dom->importNode($this->adres->toDom()->documentElement, true);

        $podmiotUpowazniony->appendChild($adres);

        if ($this->adresKoresp instanceof AdresKoresp) {
            $adresKoresp = $dom->importNode($this->adresKoresp->toDom()->documentElement, true);

            $podmiotUpowazniony->appendChild($adresKoresp);
        }

        if ( ! $this->daneKontaktowe instanceof Optional) {
            foreach ($this->daneKontaktowe as $daneKontaktowe) {
                $daneKontaktowe = $dom->importNode($daneKontaktowe->toDom()->documentElement, true);

                $podmiotUpowazniony->appendChild($daneKontaktowe);
            }
        }

        $rolaPU = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'RolaPU');
        $rolaPU->appendChild($dom->createTextNode((string) $this->rolaPU->value));

        $podmiotUpowazniony->appendChild($rolaPU);

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        $data['DaneIdentyfikacyjne'] = PodmiotUpowaznionyDaneIdentyfikacyjne::normalizeXmlArray($data['DaneIdentyfikacyjne']);

        $data['Adres'] = Adres::normalizeXmlArray($data['Adres']);

        $data['AdresKoresp'] = match (true) {
            isset($data['AdresKoresp']) => AdresKoresp::normalizeXmlArray($data['AdresKoresp']),
            default => new Optional(),
        };

        $data['DaneKontaktowe'] = match (true) {
            isset($data['DaneKontaktowe']) => array_map(
                fn (array $item): array => PodmiotUpowaznionyDaneKontaktowe::normalizeXmlArray($item),
                Arr::ensureList($data['DaneKontaktowe'])
            ),
            default => new Optional(),
        };

        return Arr::only($data, ['DaneIdentyfikacyjne', 'Adres', 'AdresKoresp', 'DaneKontaktowe', 'NrEORI', 'RolaPU']);
    }
}
