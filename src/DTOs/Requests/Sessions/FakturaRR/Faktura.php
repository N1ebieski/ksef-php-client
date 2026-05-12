<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions\FakturaRR;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\XmlDeserializableInterface;
use N1ebieski\KSEFClient\Contracts\XmlSerializableInterface;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\FakturaRR\Stopka;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\Support\Concerns\HasFromXml;
use N1ebieski\KSEFClient\Support\Concerns\HasToXml;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\Validator\Rules\Array\MaxRule;
use N1ebieski\KSEFClient\Validator\Validator;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class Faktura extends AbstractDTO implements XmlSerializableInterface, XmlDeserializableInterface, DomSerializableInterface
{
    use HasFromXml;
    use HasToXml;

    /**
     * @var Optional|array<int, Podmiot3>
     */
    public readonly Optional | array $podmiot3;

    /**
     * @param Naglowek $naglowek Zawiera dane dotyczące kodu i wariantu formularza, daty i czasu wytworzenia pliku faktury VAT RR/ faktury VAT RR KOREKTA oraz nazwy systemu teleinformatycznego, z którego korzysta nabywca.
     * @param Podmiot1 $podmiot1 Zawiera informacje, które charakteryzują dostawcę (tj. rolnika ryczałtowego).
     * @param Podmiot2 $podmiot2 Zawiera informacje, które charakteryzują nabywcę (tj. podatnika VAT czynnego będącego wystawcą faktury VAT RR/ faktury VAT RR KOREKTA).
     * @param FakturaRR $fakturaRR Zawiera szczegółowe informacje dotyczące nabycia dokumentowanego fakturą VAT RR/ fakturą VAT RR KOREKTA. W szczególności są to elementy faktury wynikające z treści obowiązujących przepisów (kwoty i pozycje faktury), jak również elementy dodatkowe dotyczące m.in. formy płatności, numerów rachunków bankowych oraz dodatkowych odliczeń lub obciążeń
     * @param Optional|array<int, Podmiot3> $podmiot3 Zawiera informacje, które charakteryzują podmiot/-y trzeci/-e (inny/-e niż dostawca i nabywca), związany/-e z fakturą VAT RR/ fakturą VAT RR KOREKTA [element fakultatywny].
     * @param Optional|Stopka $stopka Zawiera pozostałe informacje na fakturze VAT RR/ fakturze VAT RR KOREKTA np. stopkę faktury, numer KRS, REGON [element fakultatywny].
     */
    public function __construct(
        public readonly Naglowek $naglowek,
        public readonly Podmiot1 $podmiot1,
        public readonly Podmiot2 $podmiot2,
        public readonly FakturaRR $fakturaRR,
        Optional | array $podmiot3 = new Optional(),
        public readonly Optional | Stopka $stopka = new Optional(),
    ) {
        Validator::validate([
            'podmiot3' => $podmiot3,
        ], [
            'podmiot3' => [new MaxRule(100)],
        ]);

        $this->podmiot3 = $podmiot3;
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $faktura = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'Faktura');
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

        $fakturaRR = $dom->importNode($this->fakturaRR->toDom()->documentElement, true);
        $faktura->appendChild($fakturaRR);

        if ($this->stopka instanceof Stopka) {
            $stopka = $dom->importNode($this->stopka->toDom()->documentElement, true);
            $faktura->appendChild($stopka);
        }

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        $data['Naglowek'] = Naglowek::normalizeXmlArray($data['Naglowek']);

        $data['Podmiot1'] = Podmiot1::normalizeXmlArray($data['Podmiot1']);

        $data['Podmiot2'] = Podmiot2::normalizeXmlArray($data['Podmiot2']);

        $data['Podmiot3'] = match (true) {
            isset($data['Podmiot3']) => array_map(
                Podmiot3::normalizeXmlArray(...), //@phpstan-ignore-line argument.type
                Arr::ensureList($data['Podmiot3'])
            ),
            default => new Optional(),
        };

        $data['FakturaRR'] = FakturaRR::normalizeXmlArray($data['FakturaRR']);

        $data['Stopka'] = match (true) {
            isset($data['Stopka']) => Stopka::normalizeXmlArray($data['Stopka']),
            default => new Optional(),
        };

        return Arr::only($data, ['Naglowek', 'Podmiot1', 'Podmiot2', 'Podmiot3', 'FakturaRR', 'Stopka']);
    }
}
