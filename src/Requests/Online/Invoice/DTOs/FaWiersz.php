<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\CN;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\GTIN;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\GTU;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\Indeks;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\KursWaluty;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\KwotaAkcyzy;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\NrWierszaFa;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_10;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_11;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_11A;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_11Vat;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_12;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_12_XII;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_12_Zal_15;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_6A;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_7;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_8A;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_8B;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_9A;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_9B;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\PKOB;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\PKWiU;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\Procedura;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\StanPrzed;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\UU_ID;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;

final readonly class FaWiersz extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param UU_ID|Optional $uu_id Uniwersalny unikalny numer wiersza faktury
     * @param P_6A|Optional $p_6A Data dokonania lub zakończenia dostawy towarów lub wykonania usługi lub data otrzymania zapłaty, o której mowa w art. 106b ust. 1 pkt 4 ustawy, o ile taka data jest określona i różni się od daty wystawienia faktury. Pole wypełnia się dla przypadku, gdy dla poszczególnych pozycji faktury występują różne daty
     * @param P_7|Optional $p_7 Nazwa (rodzaj) towaru lub usługi. Pole opcjonalne wyłącznie dla przypadku określonego w art 106j ust. 3 pkt 2 ustawy (faktura korygująca)
     * @param Indeks|Optional $indeks Pole przeznaczone do wpisania wewnętrznego kodu towaru lub usługi nadanego przez podatnika albo dodatkowego opisu
     * @param GTIN|Optional $gtin Globalny numer jednostki handlowej
     * @param PKWiU|Optional $pkwiu Symbol Polskiej Klasyfikacji Wyrobów i Usług
     * @param CN|Optional $cn Symbol Nomenklatury Scalonej
     * @param PKOB|Optional $pkob Symbol Polskiej Klasyfikacji Obiektów Budowlanych
     * @param P_8A|Optional $p_8A Miara dostarczonych towarów lub zakres wykonanych usług. Pole opcjonalne dla przypadku określonego w art. 106e ust. 5 pkt 3 ustawy
     * @param P_8B|Optional $p_8B Ilość (liczba) dostarczonych towarów lub zakres wykonanych usług. Pole opcjonalne dla przypadku określonego w art. 106e ust. 5 pkt 3 ustawy
     * @param P_9A|Optional $p_9A Cena jednostkowa towaru lub usługi bez kwoty podatku (cena jednostkowa netto). Pole opcjonalne dla przypadków określonych w art. 106e ust. 2 i 3 oraz ust. 5 pkt 3 ustawy
     * @param P_9B|Optional $p_9B Cena wraz z kwotą podatku (cena jednostkowa brutto), w przypadku zastosowania art. 106e ust. 7 i 8 ustawy
     * @param P_10|Optional $p_10 Kwoty wszelkich opustów lub obniżek cen, w tym w formie rabatu z tytułu wcześniejszej zapłaty, o ile nie zostały one uwzględnione w cenie jednostkowej netto, a w przypadku stosowania art. 106e ust. 7 ustawy w cenie jednostkowej brutto. Pole opcjonalne dla przypadków określonych w art. 106e ust. 2 i 3 oraz ust. 5 pkt 1 ustawy
     * @param P_11|Optional $p_11 Wartość dostarczonych towarów lub wykonanych usług, objętych transakcją, bez kwoty podatku (wartość sprzedaży netto). Pole opcjonalne dla przypadków określonych w art. 106e ust. 2 i 3 oraz ust. 5 pkt 3 ustawy
     * @param P_11A|Optional $p_11A Wartość sprzedaży brutto, w przypadku zastosowania art. 106e ust. 7 i 8 ustawy
     * @param P_11Vat|Optional $p_11Vat Kwota podatku w przypadku, o którym mowa w art. 106e ust. 10 ustawy
     * @param P_12|Optional $p_12 Stawka podatku. Pole opcjonalne dla przypadków określonych w art. 106e ust. 2, 3, ust. 4 pkt 3 i ust. 5 pkt 3 ustawy
     * @param P_12_XII|Optional $p_12_XII Stawka podatku od wartości dodanej w przypadku, o którym mowa w dziale XII w rozdziale 6a ustawy
     * @param P_12_Zal_15|Optional $p_12_Zal_15 Znacznik dla towaru lub usługi wymienionych w załączniku nr 15 do ustawy - wartość "1"
     * @param KwotaAkcyzy|Optional $kwotaAkcyzy Kwota podatku akcyzowego zawarta w cenie towaru
     * @param GTU|Optional $gtu Oznaczenie dotyczące dostawy towarów i świadczenia usług
     * @param Procedura|Optional $procedura Oznaczenie dotyczące procedury
     * @param KursWaluty|Optional $kursWaluty Kurs waluty stosowany do wyliczenia kwoty podatku w przypadkach, o których mowa w Dziale VI ustawy
     * @param StanPrzed|Optional $stanPrzed Znacznik stanu przed korektą w przypadku faktury korygującej lub faktury korygującej fakturę wystawioną w związku z art. 106f ust. 3 ustawy, w przypadku gdy korekta dotyczy danych wykazanych w pozycjach faktury i jest dokonywana w sposób polegający na wykazaniu danych przed korektą i po korekcie jako osobnych wierszy z odrębną numeracją oraz w przypadku potwierdzania braku zmiany wartości danej pozycji
     * @return void
     */
    public function __construct(
        public NrWierszaFa $nrWierszaFa,
        public Optional | UU_ID $uu_id = new Optional(),
        public Optional | P_6A $p_6A = new Optional(),
        public Optional | P_7 $p_7 = new Optional(),
        public Optional | Indeks $indeks = new Optional(),
        public Optional | GTIN $gtin = new Optional(),
        public Optional | PKWiU $pkwiu = new Optional(),
        public Optional | CN $cn = new Optional(),
        public Optional | PKOB $pkob = new Optional(),
        public Optional | P_8A $p_8A = new Optional(),
        public Optional | P_8B $p_8B = new Optional(),
        public Optional | P_9A $p_9A = new Optional(),
        public Optional | P_9B $p_9B = new Optional(),
        public Optional | P_10 $p_10 = new Optional(),
        public Optional | P_11 $p_11 = new Optional(),
        public Optional | P_11A $p_11A = new Optional(),
        public Optional | P_11Vat $p_11Vat = new Optional(),
        public Optional | P_12 $p_12 = new Optional(),
        public Optional | P_12_XII $p_12_XII = new Optional(),
        public Optional | P_12_Zal_15 $p_12_Zal_15 = new Optional(),
        public Optional | KwotaAkcyzy $kwotaAkcyzy = new Optional(),
        public Optional | GTU $gtu = new Optional(),
        public Optional | Procedura $procedura = new Optional(),
        public Optional | KursWaluty $kursWaluty = new Optional(),
        public Optional | StanPrzed $stanPrzed = new Optional(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $faWiersz = $dom->createElement('FaWiersz');
        $dom->appendChild($faWiersz);

        $nrWierszaFa = $dom->createElement('NrWierszaFa');
        $nrWierszaFa->appendChild($dom->createTextNode((string) $this->nrWierszaFa));

        $faWiersz->appendChild($nrWierszaFa);

        if ($this->uu_id instanceof UU_ID) {
            $uu_id = $dom->createElement('UU_ID');
            $uu_id->appendChild($dom->createTextNode((string) $this->uu_id));

            $faWiersz->appendChild($uu_id);
        }

        if ($this->p_6A instanceof P_6A) {
            $p_6A = $dom->createElement('P_6A');
            $p_6A->appendChild($dom->createTextNode((string) $this->p_6A));

            $faWiersz->appendChild($p_6A);
        }

        if ($this->p_7 instanceof P_7) {
            $p_7 = $dom->createElement('P_7');
            $p_7->appendChild($dom->createTextNode((string) $this->p_7));

            $faWiersz->appendChild($p_7);
        }

        if ($this->indeks instanceof Indeks) {
            $indeks = $dom->createElement('Indeks');
            $indeks->appendChild($dom->createTextNode((string) $this->indeks));

            $faWiersz->appendChild($indeks);
        }

        if ($this->gtin instanceof GTIN) {
            $gtin = $dom->createElement('GTIN');
            $gtin->appendChild($dom->createTextNode((string) $this->gtin));

            $faWiersz->appendChild($gtin);
        }

        if ($this->pkwiu instanceof PKWiU) {
            $pkwiu = $dom->createElement('PKWiU');
            $pkwiu->appendChild($dom->createTextNode((string) $this->pkwiu));

            $faWiersz->appendChild($pkwiu);
        }

        if ($this->cn instanceof CN) {
            $cn = $dom->createElement('CN');
            $cn->appendChild($dom->createTextNode((string) $this->cn));

            $faWiersz->appendChild($cn);
        }

        if ($this->pkob instanceof PKOB) {
            $pkob = $dom->createElement('PKOB');
            $pkob->appendChild($dom->createTextNode((string) $this->pkob));

            $faWiersz->appendChild($pkob);
        }

        if ($this->p_8A instanceof P_8A) {
            $p_8A = $dom->createElement('P_8A');
            $p_8A->appendChild($dom->createTextNode((string) $this->p_8A));

            $faWiersz->appendChild($p_8A);
        }

        if ($this->p_8B instanceof P_8B) {
            $p_8B = $dom->createElement('P_8B');
            $p_8B->appendChild($dom->createTextNode((string) $this->p_8B));

            $faWiersz->appendChild($p_8B);
        }

        if ($this->p_9A instanceof P_9A) {
            $p_9A = $dom->createElement('P_9A');
            $p_9A->appendChild($dom->createTextNode((string) $this->p_9A));

            $faWiersz->appendChild($p_9A);
        }

        if ($this->p_9B instanceof P_9B) {
            $p_9B = $dom->createElement('P_9B');
            $p_9B->appendChild($dom->createTextNode((string) $this->p_9B));

            $faWiersz->appendChild($p_9B);
        }

        if ($this->p_10 instanceof P_10) {
            $p_10 = $dom->createElement('P_10');
            $p_10->appendChild($dom->createTextNode((string) $this->p_10));

            $faWiersz->appendChild($p_10);
        }

        if ($this->p_11 instanceof P_11) {
            $p_11 = $dom->createElement('P_11');
            $p_11->appendChild($dom->createTextNode((string) $this->p_11));

            $faWiersz->appendChild($p_11);
        }

        if ($this->p_11A instanceof P_11A) {
            $p_11A = $dom->createElement('P_11A');
            $p_11A->appendChild($dom->createTextNode((string) $this->p_11A));

            $faWiersz->appendChild($p_11A);
        }

        if ($this->p_11Vat instanceof P_11Vat) {
            $p_11Vat = $dom->createElement('P_11Vat');
            $p_11Vat->appendChild($dom->createTextNode((string) $this->p_11Vat));

            $faWiersz->appendChild($p_11Vat);
        }

        if ($this->p_12 instanceof P_12) {
            $p_12 = $dom->createElement('P_12');
            $p_12->appendChild($dom->createTextNode((string) $this->p_12));

            $faWiersz->appendChild($p_12);
        }

        if ($this->p_12_XII instanceof P_12_XII) {
            $p_12_XII = $dom->createElement('P_12_XII');
            $p_12_XII->appendChild($dom->createTextNode((string) $this->p_12_XII));

            $faWiersz->appendChild($p_12_XII);
        }

        if ($this->p_12_Zal_15 instanceof P_12_Zal_15) {
            $p_12_Zal_15 = $dom->createElement('P_12_Zal_15');
            $p_12_Zal_15->appendChild($dom->createTextNode((string) $this->p_12_Zal_15->value));

            $faWiersz->appendChild($p_12_Zal_15);
        }

        if ($this->kwotaAkcyzy instanceof KwotaAkcyzy) {
            $kwotaAkcyzy = $dom->createElement('KwotaAkcyzy');
            $kwotaAkcyzy->appendChild($dom->createTextNode((string) $this->kwotaAkcyzy));

            $faWiersz->appendChild($kwotaAkcyzy);
        }

        if ($this->gtu instanceof GTU) {
            $gtu = $dom->createElement('Gtu');
            $gtu->appendChild($dom->createTextNode((string) $this->gtu->value));

            $faWiersz->appendChild($gtu);
        }

        if ($this->procedura instanceof Procedura) {
            $procedura = $dom->createElement('Procedura');
            $procedura->appendChild($dom->createTextNode((string) $this->procedura->value));

            $faWiersz->appendChild($procedura);
        }

        if ($this->kursWaluty instanceof KursWaluty) {
            $kursWaluty = $dom->createElement('KursWaluty');
            $kursWaluty->appendChild($dom->createTextNode((string) $this->kursWaluty));

            $faWiersz->appendChild($kursWaluty);
        }

        if ($this->stanPrzed instanceof StanPrzed) {
            $stanPrzed = $dom->createElement('StanPrzed');
            $stanPrzed->appendChild($dom->createTextNode((string) $this->stanPrzed->value));

            $faWiersz->appendChild($stanPrzed);
        }

        $dom->appendChild($faWiersz);

        return $dom;
    }
}
