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
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class Platnosc extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    /**
     * @var Optional|array<int, TerminPlatnosci>
     */
    public readonly Optional | array $terminPlatnosci;

    /**
     * @var Optional|array<int, RachunekBankowy>
     */
    public readonly Optional | array $rachunekBankowy;

    /**
     * @var Optional|array<int, RachunekBankowyFaktora>
     */
    public readonly Optional | array $rachunekBankowyFaktora;

    /**
     * @param Optional|array<int, TerminPlatnosci> $terminPlatnosci
     * @param Optional|array<int, RachunekBankowy> $rachunekBankowy
     * @param Optional|array<int, RachunekBankowyFaktora> $rachunekBankowyFaktora
     */
    public function __construct(
        public readonly Optional | ZaplataGroup | ZaplataCzesciowaGroup $zaplataGroup = new Optional(),
        Optional | array $terminPlatnosci = new Optional(),
        public readonly Optional | FormaPlatnosciGroup | PlatnoscInnaGroup $platnoscGroup = new Optional(),
        Optional | array $rachunekBankowy = new Optional(),
        Optional | array $rachunekBankowyFaktora = new Optional(),
        public readonly Optional | Skonto $skonto = new Optional()
    ) {
        Validator::validate([
            'terminPlatnosci' => $terminPlatnosci,
            'rachunekBankowy' => $rachunekBankowy,
            'rachunekBankowyFaktora' => $rachunekBankowyFaktora
        ], [
            'terminPlatnosci' => [new MaxRule(100)],
            'rachunekBankowy' => [new MaxRule(100)],
            'rachunekBankowyFaktora' => [new MaxRule(20)]
        ]);

        $this->terminPlatnosci = $terminPlatnosci;
        $this->rachunekBankowy = $rachunekBankowy;
        $this->rachunekBankowyFaktora = $rachunekBankowyFaktora;
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $platnosc = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Platnosc');
        $dom->appendChild($platnosc);

        if ( ! $this->zaplataGroup instanceof Optional) {
            /** @var DOMElement $zaplataGroup */
            $zaplataGroup = $this->zaplataGroup->toDom()->documentElement;

            foreach ($zaplataGroup->childNodes as $child) {
                $platnosc->appendChild($dom->importNode($child, true));
            }
        }

        if ( ! $this->terminPlatnosci instanceof Optional) {
            foreach ($this->terminPlatnosci as $terminPlatnosci) {
                $terminPlatnosci = $dom->importNode($terminPlatnosci->toDom()->documentElement, true);

                $platnosc->appendChild($terminPlatnosci);
            }
        }

        if ( ! $this->platnoscGroup instanceof Optional) {
            /** @var DOMElement $platnoscGroup */
            $platnoscGroup = $this->platnoscGroup->toDom()->documentElement;

            foreach ($platnoscGroup->childNodes as $child) {
                $platnosc->appendChild($dom->importNode($child, true));
            }
        }

        if ( ! $this->rachunekBankowy instanceof Optional) {
            foreach ($this->rachunekBankowy as $rachunekBankowy) {
                $rachunekBankowy = $dom->importNode($rachunekBankowy->toDom()->documentElement, true);

                $platnosc->appendChild($rachunekBankowy);
            }
        }


        if ( ! $this->rachunekBankowyFaktora instanceof Optional) {
            foreach ($this->rachunekBankowyFaktora as $rachunekBankowyFaktora) {
                $rachunekBankowyFaktora = $dom->importNode($rachunekBankowyFaktora->toDom()->documentElement, true);

                $platnosc->appendChild($rachunekBankowyFaktora);
            }
        }

        if ($this->skonto instanceof Skonto) {
            $skonto = $dom->importNode($this->skonto->toDom()->documentElement, true);

            $platnosc->appendChild($skonto);
        }

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        $data['ZaplataGroup'] = match (true) {
            isset($data['Zaplacono']) => ZaplataGroup::normalizeXmlArray($data),
            isset($data['ZnacznikZaplatyCzesciowej']) => ZaplataCzesciowaGroup::normalizeXmlArray($data),
            default => new Optional(),
        };

        $data['TerminPlatnosci'] = match (true) {
            isset($data['TerminPlatnosci']) => array_map(
                fn (array $item): array => TerminPlatnosci::normalizeXmlArray($item),
                Arr::ensureList($data['TerminPlatnosci'])
            ),
            default => new Optional(),
        };

        $data['PlatnoscGroup'] = match (true) {
            isset($data['FormaPlatnosci']) => FormaPlatnosciGroup::normalizeXmlArray($data),
            isset($data['PlatnoscInna']) => PlatnoscInnaGroup::normalizeXmlArray($data),
            default => new Optional(),
        };

        $data['RachunekBankowy'] = match (true) {
            isset($data['RachunekBankowy']) => array_map(
                fn (array $item): array => RachunekBankowy::normalizeXmlArray($item),
                Arr::ensureList($data['RachunekBankowy'])
            ),
            default => new Optional(),
        };

        $data['RachunekBankowyFaktora'] = match (true) {
            isset($data['RachunekBankowyFaktora']) => array_map(
                fn (array $item): array => RachunekBankowyFaktora::normalizeXmlArray($item),
                Arr::ensureList($data['RachunekBankowyFaktora'])
            ),
            default => new Optional(),
        };

        $data['Skonto'] = match (true) {
            isset($data['Skonto']) => Skonto::normalizeXmlArray($data['Skonto']),
            default => new Optional(),
        };

        return Arr::onlyClassParameters($data, self::class);
    }
}
