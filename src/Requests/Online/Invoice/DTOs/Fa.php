<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use DOMElement;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\FP;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\KodWaluty;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\KursWalutyZ;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_13_10;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_13_11;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_13_6_1;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_13_6_2;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_13_6_3;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_13_7;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_13_8;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_13_9;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_15;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_1;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_1M;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_2;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\RodzajFaktury;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\WZ;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;

final readonly class Fa extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param KodWaluty $kodWaluty Trzyliterowy kod waluty (ISO 4217)
     * @param P_1 $p_1 Data wystawienia, z zastrzeżeniem art. 106na ust. 1 ustawy
     * @param P_1M|Optional $p_1m Miejsce wystawienia faktury
     * @param P_2 $p_2 Kolejny numer faktury, nadany w ramach jednej lub więcej serii, który w sposób jednoznaczny identyfikuje fakturę
     * @param Optional|P_13_6_1 $p_13_6_1 Suma wartości sprzedaży objętej stawką 0% z wyłączeniem wewnątrzwspólnotowej dostawy towarów i eksportu. W przypadku faktur zaliczkowych, kwota zaliczki. W przypadku faktur korygujących, kwota różnicy, o której mowa w art. 106j ust. 2 pkt 5 ustawy
     * @param Optional|P_13_6_2 $p_13_6_2 Suma wartości sprzedaży objętej stawką 0% w przypadku wewnątrzwspólnotowej dostawy towarów. W przypadku faktur korygujących, kwota różnicy, o której mowa w art. 106j ust. 2 pkt 5 ustawy
     * @param Optional|P_13_6_3 $p_13_6_3 Suma wartości sprzedaży objętej stawką 0% w przypadku eksportu. W przypadku faktur zaliczkowych, kwota zaliczki. W przypadku faktur korygujących, kwota różnicy, o której mowa w art. 106j ust. 2 pkt 5 ustawy
     * @param Optional|P_13_7 $p_13_7 Suma wartości sprzedaży netto objętej ryczałtem dla taksówek osobowych. W przypadku faktur zaliczkowych, kwota zaliczki netto. W przypadku faktur korygujących, kwota różnicy, o której mowa w art. 106j ust. 2 pkt 5 ustawy
     * @param Optional|P_13_8 $p_13_8 Suma wartości sprzedaży w przypadku dostawy towarów oraz świadczenia usług poza terytorium kraju, z wyłączeniem kwot wykazanych w polach P_13_5 i P_13_9. W przypadku faktur zaliczkowych, kwota zaliczki. W przypadku faktur korygujących, kwota różnicy wartości sprzedaży
     * @param Optional|P_13_9 $p_13_9 Suma wartości świadczenia usług, o których mowa w art. 100 ust. 1 pkt 4 ustawy. W przypadku faktur zaliczkowych, kwota zaliczki. W przypadku faktur korygujących, kwota różnicy wartości sprzedaży
     * @param Optional|P_13_10 $p_13_10 Suma wartości sprzedaży w procedurze odwrotnego obciążenia, dla której podatnikiem jest nabywca zgodnie z art. 17 ust. 1 pkt 7 i 8 ustawy oraz innych przypadków odwrotnego obciążenia występujących w obrocie krajowym. W przypadku faktur zaliczkowych, kwota zaliczki. W przypadku faktur korygujących, kwota różnicy, o której mowa w art. 106j ust. 2 pkt 5 ustawy
     * @param Optional|P_13_11 $p_13_11 Suma wartości sprzedaży w procedurze marży, o której mowa w art. 119 i art. 120 ustawy. W przypadku faktur zaliczkowych, kwota zaliczki. W przypadku faktur korygujących, kwota różnicy wartości sprzedaży
     * @param Optional|KursWalutyZ $kursWalutyZ Kurs waluty stosowany do wyliczenia kwoty podatku w przypadkach, o których mowa w przepisach Działu VI ustawy na fakturach, o których mowa w art. 106b ust. 1 pkt 4 ustawy
     * @param P_15 $p_15 Kwota należności ogółem. W przypadku faktur zaliczkowych kwota zapłaty dokumentowana fakturą. W przypadku faktur o których mowa w art. 106f ust. 3 ustawy kwota pozostała do zapłaty. W przypadku faktur korygujących korekta kwoty wynikającej z faktury korygowanej. W przypadku, o którym mowa w art. 106j ust. 3 ustawy korekta kwot wynikających z faktur korygowanych
     * @param Adnotacje $adnotacje Inne adnotacje na fakturze
     * @param FP|Optional $fP Faktura, o której mowa w art. 109 ust. 3d ustawy
     * @param array<int, DodatkowyOpis> $dodatkowyOpis Pola przeznaczone dla wykazywania dodatkowych danych na fakturze, w tym wymaganych przepisami prawa, dla których nie przewidziano innych pól/elementów
     * @param array<int, FaWiersz> $faWiersz Szczegółowe pozycje faktury w walucie, w której wystawiono fakturę - węzeł opcjonalny dla faktury zaliczkowej, faktury korygującej fakturę zaliczkową, oraz faktur korygujących dotyczących wszystkich dostaw towarów lub usług dokonanych lub świadczonych w danym okresie, o których mowa w art. 106j ust. 3 ustawy, dla których należy podać dane dotyczące opustu lub obniżki w podziale na stawki podatku i procedury w części Fa. W przypadku faktur korygujących, o których mowa w art. 106j ust. 3 ustawy, gdy opust lub obniżka ceny odnosi się do części dostaw towarów lub usług dokonanych lub świadczonych w danym okresie w części FaWiersz należy podać nazwy (rodzaje) towarów lub usług objętych korektą. W przypadku faktur, o których mowa w art. 106f ust. 3 ustawy, należy wykazać pełne wartości zamówienia lub umowy. W przypadku faktur korygujących pozycje faktury (w tym faktur korygujących faktury, o których mowa w art. 106f ust. 3 ustawy, jeśli korekta dotyczy wartości zamówienia), należy wykazać różnice wynikające z korekty poszczególnych pozycji lub dane pozycji korygowanych w stanie przed korektą i po korekcie jako osobne wiersze. W przypadku faktur korygujących faktury, o których mowa w art. 106f ust. 3 ustawy, jeśli korekta nie dotyczy wartości zamówienia i jednocześnie zmienia wysokość podstawy opodatkowania lub podatku, należy wprowadzić zapis wg stanu przed korektą i zapis w stanie po korekcie w celu potwierdzenia braku zmiany wartości danej pozycji faktury
     * @param Platnosc|Optional $platnosc Warunki płatności
     * @param WarunkiTransakcji|Optional $warunkiTransakcji Warunki transakcji, o ile występują
     * @param array<int, WZ> $wz Numery dokumentów magazynowych WZ (wydanie na zewnątrz) związane z fakturą
     * @return void
     */
    public function __construct(
        public KodWaluty $kodWaluty,
        public P_1 $p_1,
        public P_2 $p_2,
        public P_6Group | OkresFaGroup $p_6group,
        public P_15 $p_15,
        public array $wz = [],
        public Optional | P_1M $p_1m = new Optional(),
        public Optional | P_13_1Group $p_13_1group = new Optional(),
        public Optional | P_13_2Group $p_13_2group = new Optional(),
        public Optional | P_13_3Group $p_13_3group = new Optional(),
        public Optional | P_13_4Group $p_13_4group = new Optional(),
        public Optional | P_13_5Group $p_13_5group = new Optional(),
        public Optional | P_13_6_1 $p_13_6_1 = new Optional(),
        public Optional | P_13_6_2 $p_13_6_2 = new Optional(),
        public Optional | P_13_6_3 $p_13_6_3 = new Optional(),
        public Optional | P_13_7 $p_13_7 = new Optional(),
        public Optional | P_13_8 $p_13_8 = new Optional(),
        public Optional | P_13_9 $p_13_9 = new Optional(),
        public Optional | P_13_10 $p_13_10 = new Optional(),
        public Optional | P_13_11 $p_13_11 = new Optional(),
        public Optional | KursWalutyZ $kursWalutyZ = new Optional(),
        public Adnotacje $adnotacje = new Adnotacje(),
        public RodzajFaktury $rodzajFaktury = RodzajFaktury::Vat,
        public Optional | KorektaGroup $korektagroup = new Optional(),
        public Optional | FP $fP = new Optional(),
        public array $dodatkowyOpis = [],
        public array $faWiersz = [],
        public Optional | Platnosc $platnosc = new Optional(),
        public Optional | WarunkiTransakcji $warunkiTransakcji = new Optional()
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $fa = $dom->createElement('Fa');
        $dom->appendChild($fa);

        $kodWaluty = $dom->createElement('KodWaluty');
        $kodWaluty->appendChild($dom->createTextNode((string) $this->kodWaluty));

        $fa->appendChild($kodWaluty);

        $p_1 = $dom->createElement('P_1');
        $p_1->appendChild($dom->createTextNode((string) $this->p_1));

        $fa->appendChild($p_1);

        if ($this->p_1m instanceof P_1M) {
            $p_1m = $dom->createElement('P_1M');
            $p_1m->appendChild($dom->createTextNode((string) $this->p_1m));
            $fa->appendChild($p_1m);
        }

        $p_2 = $dom->createElement('P_2');
        $p_2->appendChild($dom->createTextNode((string) $this->p_2));

        $fa->appendChild($p_2);

        foreach ($this->wz as $wz) {
            $wz = $dom->createElement('WZ');
            $wz->appendChild($dom->createTextNode((string) $wz));

            $fa->appendChild($wz);
        }

        /** @var DOMElement $p_6group */
        $p_6group = $this->p_6group->toDom()->documentElement;

        foreach ($p_6group->childNodes as $child) {
            $fa->appendChild($dom->importNode($child, true));
        }

        if ($this->p_13_1group instanceof P_13_1Group) {
            /** @var DOMElement $p_13_1group */
            $p_13_1group = $this->p_13_1group->toDom()->documentElement;

            foreach ($p_13_1group->childNodes as $child) {
                $fa->appendChild($dom->importNode($child, true));
            }
        }

        if ($this->p_13_2group instanceof P_13_2Group) {
            /** @var DOMElement $p_13_2group */
            $p_13_2group = $this->p_13_2group->toDom()->documentElement;

            foreach ($p_13_2group->childNodes as $child) {
                $fa->appendChild($dom->importNode($child, true));
            }
        }

        if ($this->p_13_3group instanceof P_13_3Group) {
            /** @var DOMElement $p_13_3group */
            $p_13_3group = $this->p_13_3group->toDom()->documentElement;

            foreach ($p_13_3group->childNodes as $child) {
                $fa->appendChild($dom->importNode($child, true));
            }
        }

        if ($this->p_13_4group instanceof P_13_4Group) {
            /** @var DOMElement $p_13_4group */
            $p_13_4group = $this->p_13_4group->toDom()->documentElement;

            foreach ($p_13_4group->childNodes as $child) {
                $fa->appendChild($dom->importNode($child, true));
            }
        }

        if ($this->p_13_5group instanceof P_13_5Group) {
            /** @var DOMElement $p_13_5group */
            $p_13_5group = $this->p_13_5group->toDom()->documentElement;

            foreach ($p_13_5group->childNodes as $child) {
                $fa->appendChild($dom->importNode($child, true));
            }
        }

        if ($this->p_13_6_1 instanceof P_13_6_1) {
            $p13_6_1 = $dom->createElement('P_13_6_1');
            $p13_6_1->appendChild($dom->createTextNode((string) $this->p_13_6_1));

            $fa->appendChild($p13_6_1);
        }

        if ($this->p_13_6_2 instanceof P_13_6_2) {
            $p13_6_2 = $dom->createElement('P_13_6_2');
            $p13_6_2->appendChild($dom->createTextNode((string) $this->p_13_6_2));

            $fa->appendChild($p13_6_2);
        }

        if ($this->p_13_6_3 instanceof P_13_6_3) {
            $p13_6_3 = $dom->createElement('P_13_6_3');
            $p13_6_3->appendChild($dom->createTextNode((string) $this->p_13_6_3));

            $fa->appendChild($p13_6_3);
        }

        if ($this->p_13_7 instanceof P_13_7) {
            $p13_7 = $dom->createElement('P_13_7');
            $p13_7->appendChild($dom->createTextNode((string) $this->p_13_7));

            $fa->appendChild($p13_7);
        }

        if ($this->p_13_8 instanceof P_13_8) {
            $p13_8 = $dom->createElement('P_13_8');
            $p13_8->appendChild($dom->createTextNode((string) $this->p_13_8));

            $fa->appendChild($p13_8);
        }

        if ($this->p_13_9 instanceof P_13_9) {
            $p13_9 = $dom->createElement('P_13_9');
            $p13_9->appendChild($dom->createTextNode((string) $this->p_13_9));

            $fa->appendChild($p13_9);
        }

        if ($this->p_13_10 instanceof P_13_10) {
            $p13_10 = $dom->createElement('P_13_10');
            $p13_10->appendChild($dom->createTextNode((string) $this->p_13_10));

            $fa->appendChild($p13_10);
        }

        if ($this->p_13_11 instanceof P_13_11) {
            $p13_11 = $dom->createElement('P_13_11');
            $p13_11->appendChild($dom->createTextNode((string) $this->p_13_11));

            $fa->appendChild($p13_11);
        }

        $p_15 = $dom->createElement('P_15');
        $p_15->appendChild($dom->createTextNode((string) $this->p_15));

        $fa->appendChild($p_15);

        if ($this->kursWalutyZ instanceof KursWalutyZ) {
            $kursWalutyZ = $dom->createElement('KursWalutyZ');
            $kursWalutyZ->appendChild($dom->createTextNode((string) $this->kursWalutyZ));

            $fa->appendChild($kursWalutyZ);
        }

        $adnotacje = $dom->importNode($this->adnotacje->toDom()->documentElement, true);

        $fa->appendChild($adnotacje);

        $rodzajFaktury = $dom->createElement('RodzajFaktury');
        $rodzajFaktury->appendChild($dom->createTextNode((string) $this->rodzajFaktury->value));

        $fa->appendChild($rodzajFaktury);

        if ($this->korektagroup instanceof KorektaGroup) {
            /** @var DOMElement $korektagroup */
            $korektagroup = $this->korektagroup->toDom()->documentElement;

            foreach ($korektagroup->childNodes as $child) {
                $korektagroup->appendChild($dom->importNode($child, true));
            }
        }

        if ($this->fP instanceof FP) {
            $fP = $dom->createElement('FP');
            $fP->appendChild($dom->createTextNode((string) $this->fP->value));
            $fa->appendChild($fP);
        }

        foreach ($this->dodatkowyOpis as $dodatkowyOpis) {
            $dodatkowyOpis = $dom->importNode($dodatkowyOpis->toDom()->documentElement, true);
            $fa->appendChild($dodatkowyOpis);
        }

        foreach ($this->faWiersz as $faWiersz) {
            $faWiersz = $dom->importNode($faWiersz->toDom()->documentElement, true);
            $fa->appendChild($faWiersz);
        }

        if ($this->platnosc instanceof Platnosc) {
            $platnosc = $dom->importNode($this->platnosc->toDom()->documentElement, true);

            $fa->appendChild($platnosc);
        }

        if ($this->warunkiTransakcji instanceof WarunkiTransakcji) {
            $warunkiTransakcji = $dom->importNode($this->warunkiTransakcji->toDom()->documentElement, true);

            $fa->appendChild($warunkiTransakcji);
        }

        return $dom;
    }
}
