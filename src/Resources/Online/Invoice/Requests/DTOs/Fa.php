<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs;

use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\FP;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\KodWaluty;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_13_1;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_13_2;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_13_3;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_13_4;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_13_5;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_13_7;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_14_1;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_14_2;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_14_3;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_14_4;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_15;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_1;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_1M;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_2;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_6;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\RodzajFaktury;
use N1ebieski\KSEFClient\Support\DTO;

final readonly class Fa extends DTO
{
    /**
     * @param KodWaluty $kodWaluty Trzyliterowy kod waluty (ISO 4217)
     * @param P_1 $p_1 Data wystawienia, z zastrzeżeniem art. 106na ust. 1 ustawy
     * @param P_1M|null $p_1m Miejsce wystawienia faktury
     * @param P_2 $p_2 Kolejny numer faktury, nadany w ramach jednej lub więcej serii, który w sposób jednoznaczny identyfikuje fakturę
     * @param P_13_7|null $p_13_7 Suma wartości sprzedaży netto objętej ryczałtem dla taksówek osobowych. W przypadku faktur zaliczkowych, kwota zaliczki netto. W przypadku faktur korygujących, kwota różnicy, o której mowa w art. 106j ust. 2 pkt 5 ustawy
     * @param P_15 $p_15 Kwota należności ogółem. W przypadku faktur zaliczkowych kwota zapłaty dokumentowana fakturą. W przypadku faktur o których mowa w art. 106f ust. 3 ustawy kwota pozostała do zapłaty. W przypadku faktur korygujących korekta kwoty wynikającej z faktury korygowanej. W przypadku, o którym mowa w art. 106j ust. 3 ustawy korekta kwot wynikających z faktur korygowanych
     * @param Adnotacje $adnotacje Inne adnotacje na fakturze
     * @param FP|null $fP Faktura, o której mowa w art. 109 ust. 3d ustawy
     * @param array<int, DodatkowyOpis> $dodatkowyOpis Pola przeznaczone dla wykazywania dodatkowych danych na fakturze, w tym wymaganych przepisami prawa, dla których nie przewidziano innych pól/elementów
     * @param array<int, FaWiersz> $faWiersz Szczegółowe pozycje faktury w walucie, w której wystawiono fakturę - węzeł opcjonalny dla faktury zaliczkowej, faktury korygującej fakturę zaliczkową, oraz faktur korygujących dotyczących wszystkich dostaw towarów lub usług dokonanych lub świadczonych w danym okresie, o których mowa w art. 106j ust. 3 ustawy, dla których należy podać dane dotyczące opustu lub obniżki w podziale na stawki podatku i procedury w części Fa. W przypadku faktur korygujących, o których mowa w art. 106j ust. 3 ustawy, gdy opust lub obniżka ceny odnosi się do części dostaw towarów lub usług dokonanych lub świadczonych w danym okresie w części FaWiersz należy podać nazwy (rodzaje) towarów lub usług objętych korektą. W przypadku faktur, o których mowa w art. 106f ust. 3 ustawy, należy wykazać pełne wartości zamówienia lub umowy. W przypadku faktur korygujących pozycje faktury (w tym faktur korygujących faktury, o których mowa w art. 106f ust. 3 ustawy, jeśli korekta dotyczy wartości zamówienia), należy wykazać różnice wynikające z korekty poszczególnych pozycji lub dane pozycji korygowanych w stanie przed korektą i po korekcie jako osobne wiersze. W przypadku faktur korygujących faktury, o których mowa w art. 106f ust. 3 ustawy, jeśli korekta nie dotyczy wartości zamówienia i jednocześnie zmienia wysokość podstawy opodatkowania lub podatku, należy wprowadzić zapis wg stanu przed korektą i zapis w stanie po korekcie w celu potwierdzenia braku zmiany wartości danej pozycji faktury
     * @param Platnosc|null $platnosc Warunki płatności
     * @param WarunkiTransakcji|null $warunkiTransakcji Warunki transakcji, o ile występują
     * @return void
     */
    public function __construct(
        public KodWaluty $kodWaluty,
        public P_1 $p_1,
        public P_2 $p_2,
        public P_6Group | OkresFaGroup $p_6group,
        public P_15 $p_15,
        public ?P_13_1Group $p_13_1group = null,
        public ?P_13_2Group $p_13_2group = null,
        public ?P_13_3Group $p_13_3group = null,
        public ?P_13_4Group $p_13_4group = null,
        public ?P_13_5Group $p_13_5group = null,
        public ?P_1M $p_1m = null,
        public ?P_13_7 $p_13_7 = null,
        public Adnotacje $adnotacje = new Adnotacje(),
        public RodzajFaktury $rodzajFaktury = RodzajFaktury::Vat,
        public ?FP $fP = null,
        public array $dodatkowyOpis = [],
        public array $faWiersz = [],
        public ?Platnosc $platnosc = null,
        public ?WarunkiTransakcji $warunkiTransakcji = null
    ) {
    }
}
