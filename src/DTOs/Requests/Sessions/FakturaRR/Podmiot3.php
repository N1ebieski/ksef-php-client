<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions\FakturaRR;

use DOMDocument;
use DOMElement;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\XmlNormalizableInterface;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\FakturaRR\Adres;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\FakturaRR\AdresKoresp;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\FakturaRR\DaneKontaktowe;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\FakturaRR\Podmiot3DaneIdentyfikacyjne;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\FakturaRR\RolaGroup;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\FakturaRR\RolaInnaGroup;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\Validator\Rules\Array\MaxRule;
use N1ebieski\KSEFClient\Validator\Validator;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class Podmiot3 extends AbstractDTO implements DomSerializableInterface, XmlNormalizableInterface
{
    /**
     * @var Optional|array<int, DaneKontaktowe>
     */
    public readonly Optional | array $daneKontaktowe;

    /**
     * @param Podmiot3DaneIdentyfikacyjne $daneIdentyfikacyjne Dane identyfikujące podmiot trzeci
     * @param Optional|Adres $adres Adres podmiotu trzeciego
     * @param Optional|array<int, DaneKontaktowe> $daneKontaktowe Dane kontaktowe podmiotu trzeciego
     */
    public function __construct(
        public readonly Podmiot3DaneIdentyfikacyjne $daneIdentyfikacyjne,
        public readonly RolaGroup | RolaInnaGroup $rolaGroup,
        public readonly Optional | Adres $adres = new Optional(),
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

        $podmiot3 = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'Podmiot3');
        $dom->appendChild($podmiot3);

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

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        $data['DaneIdentyfikacyjne'] = Podmiot3DaneIdentyfikacyjne::normalizeXmlArray($data['DaneIdentyfikacyjne']);

        $data['RolaGroup'] = match (true) {
            isset($data['Rola']) => RolaGroup::normalizeXmlArray($data),
            default => RolaInnaGroup::normalizeXmlArray($data),
        };

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
                DaneKontaktowe::normalizeXmlArray(...), //@phpstan-ignore-line argument.type
                Arr::ensureList($data['DaneKontaktowe'])
            ),
            default => new Optional(),
        };

        return Arr::only($data, ['DaneIdentyfikacyjne', 'Adres', 'AdresKoresp', 'DaneKontaktowe', 'RolaGroup']);
    }
}
