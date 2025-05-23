<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\CNZ;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\GTINZ;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\GTUZ;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\IndeksZ;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\KwotaAkcyzyZ;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\NrWierszaZam;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_11NettoZ;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_11VatZ;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_12Z;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_12Z_XII;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_12Z_Zal_15;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_7Z;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_8AZ;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_8BZ;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_9AZ;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\PKOBZ;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\PKWiUZ;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\ProceduraZ;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\StanPrzedZ;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\UU_IDZ;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;

final readonly class ZamowienieWiersz extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param NrWierszaZam $nrWierszaZam Kolejny numer wiersza zamówienia lub umowy
     * @param Optional|UU_IDZ $uu_idZ Uniwersalny unikalny numer wiersza zamówienia lub umowy
     * @param Optional|P_7Z $p_7Z Nazwa (rodzaj) towaru lub usługi
     * @param Optional|IndeksZ $indeksZ Pole przeznaczone do wpisania wewnętrznego kodu towaru lub usługi nadanego przez podatnika albo dodatkowego opisu
     * @param Optional|GTINZ $gtinZ Globalny numer jednostki handlowej
     * @param Optional|PKWiUZ $pkwiuZ Symbol Polskiej Klasyfikacji Wyrobów i Usług
     * @param Optional|CNZ $cnZ Symbol Nomenklatury Scalonej
     * @param Optional|PKOBZ $pkobZ Symbol Polskiej Klasyfikacji Obiektów Budowlanych
     * @param Optional|P_8AZ $p_8AZ Miara zamówionego towaru lub zakres usługi
     * @param Optional|P_8BZ $p_8BZ Ilość zamówionego towaru lub zakres usługi
     * @param Optional|P_9AZ $p_9AZ Cena jednostkowa netto
     * @param Optional|P_11NettoZ $p_11NettoZ Wartość zamówionego towaru lub usługi bez kwoty podatku
     * @param Optional|P_11VatZ $p_11VatZ Kwota podatku od zamówionego towaru lub usługi
     * @param Optional|P_12Z $p_12Z Stawka podatku
     * @param Optional|P_12Z_XII $p_12Z_XII Stawka podatku od wartości dodanej w przypadku, o którym mowa w dziale XII w rozdziale 6a ustawy
     * @param Optional|P_12Z_Zal_15 $p_12Z_Zal_15 Znacznik dla towaru lub usługi wymienionych w załączniku nr 15 do ustawy - wartość "1"
     * @param Optional|GTUZ $gtuZ Oznaczenie dotyczące dostawy towarów i świadczenia usług
     * @param Optional|ProceduraZ $proceduraZ Oznaczenia dotyczące procedur
     * @param Optional|KwotaAkcyzyZ $kwotaAkcyzyZ Kwota podatku akcyzowego zawarta w cenie towaru
     * @param Optional|StanPrzedZ $stanPrzedZ Znacznik stanu przed korektą w przypadku faktury korygującej fakturę dokumentującą otrzymanie zapłaty lub jej części przed dokonaniem czynności oraz fakturę wystawioną w związku z art. 106f ust. 4 ustawy, w przypadku gdy korekta dotyczy danych wykazanych w pozycjach zamówienia i jest dokonywana w sposób polegający na wykazaniu danych przed korektą i po korekcie jako osobnych wierszy z odrębną numeracją oraz w przypadku potwierdzania braku zmiany wartości danej pozycji
     */
    public function __construct(
        public NrWierszaZam $nrWierszaZam,
        public Optional | UU_IDZ $uu_idZ = new Optional(),
        public Optional | P_7Z $p_7Z = new Optional(),
        public Optional | IndeksZ $indeksZ = new Optional(),
        public Optional | GTINZ $gtinZ = new Optional(),
        public Optional | PKWiUZ $pkwiuZ = new Optional(),
        public Optional | CNZ $cnZ = new Optional(),
        public Optional | PKOBZ $pkobZ = new Optional(),
        public Optional | P_8AZ $p_8AZ = new Optional(),
        public Optional | P_8BZ $p_8BZ = new Optional(),
        public Optional | P_9AZ $p_9AZ = new Optional(),
        public Optional | P_11NettoZ $p_11NettoZ = new Optional(),
        public Optional | P_11VatZ $p_11VatZ = new Optional(),
        public Optional | P_12Z $p_12Z = new Optional(),
        public Optional | P_12Z_XII $p_12Z_XII = new Optional(),
        public Optional | P_12Z_Zal_15 $p_12Z_Zal_15 = new Optional(),
        public Optional | GTUZ $gtuZ = new Optional(),
        public Optional | ProceduraZ $proceduraZ = new Optional(),
        public Optional | KwotaAkcyzyZ $kwotaAkcyzyZ = new Optional(),
        public Optional | StanPrzedZ $stanPrzedZ = new Optional(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $zamowienieWiersz = $dom->createElement('ZamowienieWiersz');
        $dom->appendChild($zamowienieWiersz);

        $nrWierszaZam = $dom->createElement('NrWierszaZam');
        $nrWierszaZam->appendChild($dom->createTextNode((string) $this->nrWierszaZam));

        $zamowienieWiersz->appendChild($nrWierszaZam);

        if ($this->uu_idZ instanceof UU_IDZ) {
            $uu_idZ = $dom->createElement('UU_IDZ');
            $uu_idZ->appendChild($dom->createTextNode((string) $this->uu_idZ));

            $zamowienieWiersz->appendChild($uu_idZ);
        }

        if ($this->p_7Z instanceof P_7Z) {
            $p_7Z = $dom->createElement('P_7Z');
            $p_7Z->appendChild($dom->createTextNode((string) $this->p_7Z));

            $zamowienieWiersz->appendChild($p_7Z);
        }

        if ($this->indeksZ instanceof IndeksZ) {
            $indeksZ = $dom->createElement('IndeksZ');
            $indeksZ->appendChild($dom->createTextNode((string) $this->indeksZ));

            $zamowienieWiersz->appendChild($indeksZ);
        }

        if ($this->gtinZ instanceof GTINZ) {
            $gtinZ = $dom->createElement('GTINZ');
            $gtinZ->appendChild($dom->createTextNode((string) $this->gtinZ));

            $zamowienieWiersz->appendChild($gtinZ);
        }

        if ($this->pkwiuZ instanceof PKWiUZ) {
            $pkwiuZ = $dom->createElement('PKWiUZ');
            $pkwiuZ->appendChild($dom->createTextNode((string) $this->pkwiuZ));

            $zamowienieWiersz->appendChild($pkwiuZ);
        }

        if ($this->cnZ instanceof CNZ) {
            $cnZ = $dom->createElement('CNZ');
            $cnZ->appendChild($dom->createTextNode((string) $this->cnZ));

            $zamowienieWiersz->appendChild($cnZ);
        }

        if ($this->pkobZ instanceof PKOBZ) {
            $pkobZ = $dom->createElement('PKOBZ');
            $pkobZ->appendChild($dom->createTextNode((string) $this->pkobZ));

            $zamowienieWiersz->appendChild($pkobZ);
        }

        if ($this->p_8AZ instanceof P_8AZ) {
            $p_8AZ = $dom->createElement('P_8AZ');
            $p_8AZ->appendChild($dom->createTextNode((string) $this->p_8AZ));

            $zamowienieWiersz->appendChild($p_8AZ);
        }

        if ($this->p_8BZ instanceof P_8BZ) {
            $p_8BZ = $dom->createElement('P_8BZ');
            $p_8BZ->appendChild($dom->createTextNode((string) $this->p_8BZ));

            $zamowienieWiersz->appendChild($p_8BZ);
        }

        if ($this->p_9AZ instanceof P_9AZ) {
            $p_9AZ = $dom->createElement('P_9AZ');
            $p_9AZ->appendChild($dom->createTextNode((string) $this->p_9AZ));

            $zamowienieWiersz->appendChild($p_9AZ);
        }

        if ($this->p_11NettoZ instanceof P_11NettoZ) {
            $p_11NettoZ = $dom->createElement('P_11NettoZ');
            $p_11NettoZ->appendChild($dom->createTextNode((string) $this->p_11NettoZ));

            $zamowienieWiersz->appendChild($p_11NettoZ);
        }

        if ($this->p_11VatZ instanceof P_11VatZ) {
            $p_11VatZ = $dom->createElement('P_11VatZ');
            $p_11VatZ->appendChild($dom->createTextNode((string) $this->p_11VatZ));

            $zamowienieWiersz->appendChild($p_11VatZ);
        }

        if ($this->p_12Z instanceof P_12Z) {
            $p_12Z = $dom->createElement('P_12Z');
            $p_12Z->appendChild($dom->createTextNode((string) $this->p_12Z->value));

            $zamowienieWiersz->appendChild($p_12Z);
        }

        if ($this->p_12Z_XII instanceof P_12Z_XII) {
            $p_12Z_XII = $dom->createElement('P_12Z_XII');
            $p_12Z_XII->appendChild($dom->createTextNode((string) $this->p_12Z_XII));

            $zamowienieWiersz->appendChild($p_12Z_XII);
        }

        if ($this->p_12Z_Zal_15 instanceof P_12Z_Zal_15) {
            $p_12Z_Zal_15 = $dom->createElement('P_12Z_Zal_15');
            $p_12Z_Zal_15->appendChild($dom->createTextNode((string) $this->p_12Z_Zal_15->value));

            $zamowienieWiersz->appendChild($p_12Z_Zal_15);
        }

        if ($this->gtuZ instanceof GTUZ) {
            $gtuZ = $dom->createElement('GTUZ');
            $gtuZ->appendChild($dom->createTextNode((string) $this->gtuZ->value));

            $zamowienieWiersz->appendChild($gtuZ);
        }

        if ($this->proceduraZ instanceof ProceduraZ) {
            $proceduraZ = $dom->createElement('ProceduraZ');
            $proceduraZ->appendChild($dom->createTextNode((string) $this->proceduraZ->value));

            $zamowienieWiersz->appendChild($proceduraZ);
        }

        if ($this->kwotaAkcyzyZ instanceof KwotaAkcyzyZ) {
            $kwotaAkcyzyZ = $dom->createElement('KwotaAkcyzyZ');
            $kwotaAkcyzyZ->appendChild($dom->createTextNode((string) $this->kwotaAkcyzyZ));

            $zamowienieWiersz->appendChild($kwotaAkcyzyZ);
        }

        if ($this->stanPrzedZ instanceof StanPrzedZ) {
            $stanPrzedZ = $dom->createElement('StanPrzedZ');
            $stanPrzedZ->appendChild($dom->createTextNode((string) $this->stanPrzedZ->value));

            $zamowienieWiersz->appendChild($stanPrzedZ);
        }

        return $dom;
    }
}
