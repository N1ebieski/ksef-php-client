<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use DOMElement;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\Validator\Rules\Array\MaxRule;
use N1ebieski\KSEFClient\Validator\Validator;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\SumaObciazen;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\SumaOdliczen;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class Rozliczenie extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    /**
     * @var Optional|array<int, Obciazenia>
     */
    public readonly Optional | array $obciazenia;

    /**
     * @var Optional|array<int, Odliczenia>
     */
    public readonly Optional | array $odliczenia;

    /**
     * @param Optional|array<int, Obciazenia> $obciazenia
     * @param Optional|array<int, Odliczenia> $odliczenia
     */
    public function __construct(
        Optional | array $obciazenia = new Optional(),
        public readonly Optional | SumaObciazen $sumaObciazen = new Optional(),
        Optional | array $odliczenia = new Optional(),
        public readonly Optional | SumaOdliczen $sumaOdliczen = new Optional(),
        public readonly Optional | RozliczenieGroup $rozliczenieGroup = new Optional()
    ) {
        Validator::validate([
            'obciazenia' => $obciazenia,
            'odliczenia' => $odliczenia
        ], [
            'obciazenia' => [new MaxRule(100)],
            'odliczenia' => [new MaxRule(100)]
        ]);

        $this->obciazenia = $obciazenia;
        $this->odliczenia = $odliczenia;
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $rozliczenie = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Rozliczenie');
        $dom->appendChild($rozliczenie);

        if ( ! $this->obciazenia instanceof Optional) {
            foreach ($this->obciazenia as $obciazenia) {
                $obciazenia = $dom->importNode($obciazenia->toDom()->documentElement, true);

                $rozliczenie->appendChild($obciazenia);
            }
        }

        if ($this->sumaObciazen instanceof SumaObciazen) {
            $sumaObciazen = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'SumaObciazen');
            $sumaObciazen->appendChild($dom->createTextNode((string) $this->sumaObciazen));

            $rozliczenie->appendChild($sumaObciazen);
        }

        if ( ! $this->odliczenia instanceof Optional) {
            foreach ($this->odliczenia as $odliczenia) {
                $odliczenia = $dom->importNode($odliczenia->toDom()->documentElement, true);

                $rozliczenie->appendChild($odliczenia);
            }
        }

        if ($this->sumaOdliczen instanceof SumaOdliczen) {
            $sumaOdliczen = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'SumaOdliczen');
            $sumaOdliczen->appendChild($dom->createTextNode((string) $this->sumaOdliczen));

            $rozliczenie->appendChild($sumaOdliczen);
        }

        if ($this->rozliczenieGroup instanceof RozliczenieGroup) {
            /** @var DOMElement $rozliczenieGroup */
            $rozliczenieGroup = $this->rozliczenieGroup->toDom()->documentElement;

            foreach ($rozliczenieGroup->childNodes as $child) {
                $rozliczenie->appendChild($dom->importNode($child, true));
            }
        }

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        $data['Obciazenia'] = match (true) {
            isset($data['Obciazenia']) => array_map(
                fn (array $item): array => Obciazenia::normalizeXmlArray($item),
                Arr::ensureList($data['Obciazenia'])
            ),
            default => new Optional(),
        };

        $data['Odliczenia'] = match (true) {
            isset($data['Odliczenia']) => array_map(
                fn (array $item): array => Odliczenia::normalizeXmlArray($item),
                Arr::ensureList($data['Odliczenia'])
            ),
            default => new Optional(),
        };

        $data['RozliczenieGroup'] = match (true) {
            isset($data['DoZaplaty']) || isset($data['DoRozliczenia']) => RozliczenieGroup::normalizeXmlArray($data),
            default => new Optional(),
        };

        return Arr::onlyClassParameters($data, self::class);
    }
}
