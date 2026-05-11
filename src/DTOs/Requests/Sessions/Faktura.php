<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\XmlDeserializableInterface;
use N1ebieski\KSEFClient\Contracts\XmlSerializableInterface;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\Fa;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\Naglowek;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\Podmiot1;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\Podmiot2;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\Podmiot3;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\PodmiotUpowazniony;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\Stopka;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Concerns\HasFromXml;
use N1ebieski\KSEFClient\Support\Concerns\HasToXml;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\Validator\Rules\Array\MaxRule;
use N1ebieski\KSEFClient\Validator\Validator;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class Faktura extends AbstractDTO implements XmlSerializableInterface, XmlDeserializableInterface, DomSerializableInterface
{
    use HasToXml;
    use HasFromXml;

    /**
     * @var Optional|array<int, Podmiot3>
     */
    public readonly Optional | array $podmiot3;

    /**
     * @param Podmiot1 $podmiot1 Dane podatnika. Imię i nazwisko lub nazwa sprzedawcy towarów lub usług
     * @param Podmiot2 $podmiot2 Dane nabywcy
     * @param Optional|array<int, Podmiot3> $podmiot3 Dane podmiotu/-ów trzeciego/-ich (innego/-ych niż sprzedawca i nabywca wymieniony w części Podmiot2), związanego/-ych z fakturą
     * @param PodmiotUpowazniony|Optional $podmiotUpowazniony Dane podmiotu upoważnionego, związanego z fakturą
     * @param Fa $fa Na podstawie art. 106a - 106q ustawy. Pola dotyczące wartości sprzedaży i podatku wypełnia się w walucie, w której wystawiono fakturę, z wyjątkiem pól dotyczących podatku przeliczonego zgodnie z przepisami Działu VI w związku z art. 106e ust. 11 ustawy. W przypadku wystawienia faktury korygującej, wypełnia się wszystkie pola wg stanu po korekcie, a pola dotyczące podstaw opodatkowania, podatku oraz należności ogółem wypełnia się poprzez różnicę
     * @param Optional|Stopka $stopka Pozostałe dane na fakturze
     * @param Optional|Zalacznik $zalacznik Zawiera załącznik do faktury dotyczącej czynności o złożonej liczbie danych w zakresie jednostek miary i ilości (liczby) dostarczanych towarów lub wykonywanych usług lub cen jednostkowych netto [element fakultatywny].
     */
    public function __construct(
        public readonly Naglowek $naglowek,
        public readonly Podmiot1 $podmiot1,
        public readonly Podmiot2 $podmiot2,
        public readonly Fa $fa,
        Optional | array $podmiot3 = new Optional(),
        public readonly Optional | PodmiotUpowazniony $podmiotUpowazniony = new Optional(),
        public readonly Optional | Stopka $stopka = new Optional(),
        public readonly Optional | Zalacznik $zalacznik = new Optional()
    ) {
        Validator::validate([
            'podmiot3' => $podmiot3
        ], [
            'podmiot3' => [new MaxRule(100)]
        ]);

        $this->podmiot3 = $podmiot3;
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $faktura = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Faktura');

        $dom->appendChild($faktura);

        $naglowek = $dom->importNode($this->naglowek->toDom()->documentElement, true);

        $faktura->appendChild($naglowek);

        $podmiot1 = $dom->importNode($this->podmiot1->toDom()->documentElement, true);

        $faktura->appendChild($podmiot1);

        $podmiot2 = $dom->importNode($this->podmiot2->toDom()->documentElement, true);

        $faktura->appendChild($podmiot2);

        if ( ! $this->podmiot3 instanceof Optional) {
            foreach ($this->podmiot3 as $podmiot3) {
                $_podmiot3 = $dom->importNode($podmiot3->toDom()->documentElement, true);

                $faktura->appendChild($_podmiot3);
            }
        }

        $fa = $dom->importNode($this->fa->toDom()->documentElement, true);

        $faktura->appendChild($fa);

        if ($this->stopka instanceof Stopka) {
            $stopka = $dom->importNode($this->stopka->toDom()->documentElement, true);

            $faktura->appendChild($stopka);
        }

        if ($this->zalacznik instanceof Zalacznik) {
            $zalacznik = $dom->importNode($this->zalacznik->toDom()->documentElement, true);

            $faktura->appendChild($zalacznik);
        }

        return $dom;
    }

    public static function fromXmlArray(array $data): self
    {
        if (isset($data['Naglowek'])) {
            $data['Naglowek'] = Naglowek::fromXmlArray($data['Naglowek']);
        }

        if (isset($data['Podmiot1'])) {
            $data['Podmiot1'] = Podmiot1::fromXmlArray($data['Podmiot1']);
        }

        if (isset($data['Podmiot2'])) {
            $data['Podmiot2'] = Podmiot2::fromXmlArray($data['Podmiot2']);
        }

        if (isset($data['Podmiot3'])) {
            $data['Podmiot3'] = array_map(
                fn (array $item) => Podmiot3::fromXmlArray($item),
                self::ensureList($data['Podmiot3'])
            );
        }

        if (isset($data['PodmiotUpowazniony'])) {
            $data['PodmiotUpowazniony'] = PodmiotUpowazniony::fromXmlArray($data['PodmiotUpowazniony']);
        }

        if (isset($data['Fa'])) {
            $data['Fa'] = Fa::fromXmlArray($data['Fa']);
        }

        if (isset($data['Stopka'])) {
            $data['Stopka'] = Stopka::fromXmlArray($data['Stopka']);
        }

        if (isset($data['Zalacznik'])) {
            $data['Zalacznik'] = Zalacznik::fromXmlArray($data['Zalacznik']);
        }

        return new self(
            naglowek: $data['Naglowek'],
            podmiot1: $data['Podmiot1'],
            podmiot2: $data['Podmiot2'],
            fa: $data['Fa'],
            podmiot3: $data['Podmiot3'] ?? new Optional(),
            podmiotUpowazniony: $data['PodmiotUpowazniony'] ?? new Optional(),
            stopka: $data['Stopka'] ?? new Optional(),
            zalacznik: $data['Zalacznik'] ?? new Optional(),
        );
    }
}
