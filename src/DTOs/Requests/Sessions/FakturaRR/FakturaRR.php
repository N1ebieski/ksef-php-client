<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions\FakturaRR;

use DOMDocument;
use DOMElement;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\XmlNormalizableInterface;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\FakturaRR\DodatkowyOpis;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\FakturaRR\Platnosc;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\FakturaRR\Rozliczenie;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\Validator\Rules\Array\MaxRule;
use N1ebieski\KSEFClient\Validator\Validator;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\FakturaRR\P_1M;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\FakturaRR\P_4A;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\FakturaRR\P_4B;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\FakturaRR\P_4C;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\FakturaRR\RodzajFaktury;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\KodWaluty;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class FakturaRR extends AbstractDTO implements DomSerializableInterface, XmlNormalizableInterface
{
    /**
     * @var Optional|array<int, DokumentZaplaty>
     */
    public readonly Optional | array $dokumentZaplaty;

    /**
     * @var Optional|array<int, DodatkowyOpis>
     */
    public readonly Optional | array $dodatkowyOpis;

    /**
     * @var Optional|array<int, FakturaRRWiersz>
     */
    public readonly Optional | array $fakturaRRWiersz;

    /**
     * @param P_4B $p_4B Data wystawienia faktury VAT RR/ faktury VAT RR KOREKTA. Podaje się: datę wystawienia faktury VAT RR, o której mowa w art. 116 ust. 2 pkt 4 ustawy lub datę wystawienia faktury VAT RR KOREKTA, o której mowa w art. 116 ust. 5e pkt 1 ustawy w formacie RRRR-MM-DD (np. 2026-04-01).
     * @param P_4C $p_4C Kolejny numer faktury VAT RR, o którym mowa w art. 116 ust. 2 pkt 4 ustawy/ faktury VAT RR KOREKTA, o którym mowa w art. 116 ust. 5e pkt 1 ustawy.
     * @param Optional|array<int, DokumentZaplaty> $dokumentZaplaty Dane dokumentu/-ów zapłaty [element opcjonalny]. Element zawierający dane (tj. numer, data) dokumentu/-ów potwierdzającego/-ych zapłatę zaliczki lub stwierdzającego/-ych dokonanie zapłaty za nabyte produkty rolne lub usługi rolnicze
     * @param Optional|array<int, DodatkowyOpis> $dodatkowyOpis Element przeznaczony dla wykazywania dodatkowych danych na fakturze VAT RR/ fakturze VAT RR KOREKTA, w tym wymaganych przepisami prawa, dla których nie przewidziano innych pól/elementów
     * @param Optional|array<int, FakturaRRWiersz> $fakturaRRWiersz Szczegółowe pozycje faktury VAT RR/faktury VAT RR KOREKTA. Element zawierający informacje dotyczące nabywanego produktu rolnego lub usługi rolniczej m.in. nazwę produktu lub usługi, jednostkę miary, ilość, stawkę i kwotę zryczałtowanego zwrotu podatku.
     * @param Optional|P_1M $p_1M Miejsce wystawienia faktury VAT RR/ faktury VAT RR KOREKTA
     * @param Optional|P_4A $p_4A Data dokonania nabycia.
     * @param Optional|Rozliczenie $rozliczenie Dodatkowe rozliczenia na fakturze VAT RR/ fakturze VAT RR KOREKTA
     * @param Optional|Platnosc $platnosc Warunki płatności
     */
    public function __construct(
        public readonly KodWaluty $kodWaluty,
        public readonly P_4B $p_4B,
        public readonly P_4C $p_4C,
        public readonly P_11_1Group $p_11_1Group,
        public readonly P_12_1Group $p_12_1Group,
        public readonly RodzajFaktury $rodzajFaktury = RodzajFaktury::VatRr,
        public readonly Optional | KorektaGroup $korektaGroup = new Optional(),
        Optional | array $dokumentZaplaty = new Optional(),
        Optional | array $dodatkowyOpis = new Optional(),
        Optional | array $fakturaRRWiersz = new Optional(),
        public readonly Optional | P_1M $p_1M = new Optional(),
        public readonly Optional | P_4A $p_4A = new Optional(),
        public readonly Optional | Rozliczenie $rozliczenie = new Optional(),
        public readonly Optional | Platnosc $platnosc = new Optional(),
    ) {
        Validator::validate([
            'dokumentZaplaty' => $dokumentZaplaty,
            'dodatkowyOpis' => $dodatkowyOpis,
            'fakturaRRWiersz' => $fakturaRRWiersz,
        ], [
            'dokumentZaplaty' => [new MaxRule(50)],
            'dodatkowyOpis' => [new MaxRule(10000)],
            'fakturaRRWiersz' => [new MaxRule(10000)],
        ]);

        $this->dokumentZaplaty = $dokumentZaplaty;
        $this->dodatkowyOpis = $dodatkowyOpis;
        $this->fakturaRRWiersz = $fakturaRRWiersz;
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $fakturaRR = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'FakturaRR');
        $dom->appendChild($fakturaRR);

        $kodWaluty = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'KodWaluty');
        $kodWaluty->appendChild($dom->createTextNode((string) $this->kodWaluty));

        $fakturaRR->appendChild($kodWaluty);

        if ($this->p_1M instanceof P_1M) {
            $p_1M = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'P_1M');
            $p_1M->appendChild($dom->createTextNode((string) $this->p_1M));
            $fakturaRR->appendChild($p_1M);
        }

        if ($this->p_4A instanceof P_4A) {
            $p_4A = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'P_4A');
            $p_4A->appendChild($dom->createTextNode((string) $this->p_4A));
            $fakturaRR->appendChild($p_4A);
        }

        $p_4B = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'P_4B');
        $p_4B->appendChild($dom->createTextNode((string) $this->p_4B));

        $fakturaRR->appendChild($p_4B);

        $p_4C = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'P_4C');
        $p_4C->appendChild($dom->createTextNode((string) $this->p_4C));

        $fakturaRR->appendChild($p_4C);

        /** @var DOMElement $p_11_1Group */
        $p_11_1Group = $this->p_11_1Group->toDom()->documentElement;

        foreach ($p_11_1Group->childNodes as $child) {
            $fakturaRR->appendChild($dom->importNode($child, true));
        }

        /** @var DOMElement $p_12_1Group */
        $p_12_1Group = $this->p_12_1Group->toDom()->documentElement;

        foreach ($p_12_1Group->childNodes as $child) {
            $fakturaRR->appendChild($dom->importNode($child, true));
        }

        $rodzajFaktury = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'RodzajFaktury');
        $rodzajFaktury->appendChild($dom->createTextNode((string) $this->rodzajFaktury->value));

        $fakturaRR->appendChild($rodzajFaktury);

        if ($this->korektaGroup instanceof KorektaGroup) {
            /** @var DOMElement $korektaGroup */
            $korektaGroup = $this->korektaGroup->toDom()->documentElement;

            foreach ($korektaGroup->childNodes as $child) {
                $fakturaRR->appendChild($dom->importNode($child, true));
            }
        }

        if ( ! $this->dokumentZaplaty instanceof Optional) {
            foreach ($this->dokumentZaplaty as $dokumentZaplaty) {
                $dokumentZaplaty = $dom->importNode($dokumentZaplaty->toDom()->documentElement, true);
                $fakturaRR->appendChild($dokumentZaplaty);
            }
        }

        if ( ! $this->dodatkowyOpis instanceof Optional) {
            foreach ($this->dodatkowyOpis as $dodatkowyOpis) {
                $dodatkowyOpis = $dom->importNode($dodatkowyOpis->toDom()->documentElement, true);
                $fakturaRR->appendChild($dodatkowyOpis);
            }
        }

        if ( ! $this->fakturaRRWiersz instanceof Optional) {
            foreach ($this->fakturaRRWiersz as $fakturaRRWiersz) {
                $fakturaRRWiersz = $dom->importNode($fakturaRRWiersz->toDom()->documentElement, true);
                $fakturaRR->appendChild($fakturaRRWiersz);
            }
        }

        if ($this->rozliczenie instanceof Rozliczenie) {
            $rozliczenie = $dom->importNode($this->rozliczenie->toDom()->documentElement, true);
            $fakturaRR->appendChild($rozliczenie);
        }

        if ($this->platnosc instanceof Platnosc) {
            $platnosc = $dom->importNode($this->platnosc->toDom()->documentElement, true);
            $fakturaRR->appendChild($platnosc);
        }

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        $data['P_11_1Group'] = P_11_1Group::normalizeXmlArray($data);

        $data['P_12_1Group'] = P_12_1Group::normalizeXmlArray($data);

        $data['KorektaGroup'] = match (true) {
            isset($data['DaneFaKorygowanej']) => KorektaGroup::normalizeXmlArray($data),
            default => new Optional(),
        };

        $data['DokumentZaplaty'] = match (true) {
            isset($data['DokumentZaplaty']) => array_map(
                DokumentZaplaty::normalizeXmlArray(...), //@phpstan-ignore-line argument.type
                Arr::ensureList($data['DokumentZaplaty'])
            ),
            default => new Optional(),
        };

        $data['DodatkowyOpis'] = match (true) {
            isset($data['DodatkowyOpis']) => array_map(
                DodatkowyOpis::normalizeXmlArray(...), //@phpstan-ignore-line argument.type
                Arr::ensureList($data['DodatkowyOpis'])
            ),
            default => new Optional(),
        };

        $data['FakturaRRWiersz'] = match (true) {
            isset($data['FakturaRRWiersz']) => array_map(
                FakturaRRWiersz::normalizeXmlArray(...), //@phpstan-ignore-line argument.type
                Arr::ensureList($data['FakturaRRWiersz'])
            ),
            default => new Optional(),
        };

        $data['Rozliczenie'] = match (true) {
            isset($data['Rozliczenie']) => Rozliczenie::normalizeXmlArray($data['Rozliczenie']),
            default => new Optional(),
        };

        $data['Platnosc'] = match (true) {
            isset($data['Platnosc']) => Platnosc::normalizeXmlArray($data['Platnosc']),
            default => new Optional(),
        };

        return Arr::only($data, ['KodWaluty', 'P_4B', 'P_4C', 'P_11_1Group', 'P_12_1Group', 'RodzajFaktury', 'KorektaGroup', 'DokumentZaplaty', 'DodatkowyOpis', 'FakturaRRWiersz', 'P_1M', 'P_4A', 'Rozliczenie', 'Platnosc']);
    }
}
