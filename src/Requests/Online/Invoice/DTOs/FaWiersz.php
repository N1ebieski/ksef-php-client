<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\NrWierszaFa;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_11;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_12;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_7;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_8A;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_8B;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_9A;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\StanPrzed;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\UU_ID;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;

final readonly class FaWiersz extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param UU_ID|Optional $uu_id Uniwersalny unikalny numer wiersza faktury
     * @param P_7|Optional $p_7 Nazwa (rodzaj) towaru lub usługi. Pole opcjonalne wyłącznie dla przypadku określonego w art 106j ust. 3 pkt 2 ustawy (faktura korygująca)
     * @param P_8A|Optional $p_8a Miara dostarczonych towarów lub zakres wykonanych usług. Pole opcjonalne dla przypadku określonego w art. 106e ust. 5 pkt 3 ustawy
     * @param P_8B|Optional $p_8b Ilość (liczba) dostarczonych towarów lub zakres wykonanych usług. Pole opcjonalne dla przypadku określonego w art. 106e ust. 5 pkt 3 ustawy
     * @param P_9A|Optional $p_9a Cena jednostkowa towaru lub usługi bez kwoty podatku (cena jednostkowa netto). Pole opcjonalne dla przypadków określonych w art. 106e ust. 2 i 3 oraz ust. 5 pkt 3 ustawy
     * @param P_11|Optional $p_11 Wartość dostarczonych towarów lub wykonanych usług, objętych transakcją, bez kwoty podatku (wartość sprzedaży netto). Pole opcjonalne dla przypadków określonych w art. 106e ust. 2 i 3 oraz ust. 5 pkt 3 ustawy
     * @param P_12|Optional $p_12 Stawka podatku. Pole opcjonalne dla przypadków określonych w art. 106e ust. 2, 3, ust. 4 pkt 3 i ust. 5 pkt 3 ustawy
     * @param StanPrzed|Optional $stanPrzed Znacznik stanu przed korektą w przypadku faktury korygującej lub faktury korygującej fakturę wystawioną w związku z art. 106f ust. 3 ustawy, w przypadku gdy korekta dotyczy danych wykazanych w pozycjach faktury i jest dokonywana w sposób polegający na wykazaniu danych przed korektą i po korekcie jako osobnych wierszy z odrębną numeracją oraz w przypadku potwierdzania braku zmiany wartości danej pozycji
     * @return void
     */
    public function __construct(
        public NrWierszaFa $nrWierszaFa,
        public Optional | UU_ID $uu_id = new Optional(),
        public Optional | P_7 $p_7 = new Optional(),
        public Optional | P_8A $p_8a = new Optional(),
        public Optional | P_8B $p_8b = new Optional(),
        public Optional | P_9A $p_9a = new Optional(),
        public Optional | P_11 $p_11 = new Optional(),
        public Optional | P_12 $p_12 = new Optional(),
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

        if ($this->p_7 instanceof P_7) {
            $p_7 = $dom->createElement('P_7');
            $p_7->appendChild($dom->createTextNode((string) $this->p_7));
            $faWiersz->appendChild($p_7);
        }

        if ($this->p_8a instanceof P_8A) {
            $p_8a = $dom->createElement('P_8A');
            $p_8a->appendChild($dom->createTextNode((string) $this->p_8a));
            $faWiersz->appendChild($p_8a);
        }

        if ($this->p_8b instanceof P_8B) {
            $p_8b = $dom->createElement('P_8B');
            $p_8b->appendChild($dom->createTextNode((string) $this->p_8b));
            $faWiersz->appendChild($p_8b);
        }

        if ($this->p_9a instanceof P_9A) {
            $p_9a = $dom->createElement('P_9A');
            $p_9a->appendChild($dom->createTextNode((string) $this->p_9a));
            $faWiersz->appendChild($p_9a);
        }

        if ($this->p_11 instanceof P_11) {
            $p_11 = $dom->createElement('P_11');
            $p_11->appendChild($dom->createTextNode((string) $this->p_11));
            $faWiersz->appendChild($p_11);
        }

        if ($this->p_12 instanceof P_12) {
            $p_12 = $dom->createElement('P_12');
            $p_12->appendChild($dom->createTextNode((string) $this->p_12->value));
            $faWiersz->appendChild($p_12);
        }

        if ($this->stanPrzed instanceof StanPrzed) {
            $stanPrzed = $dom->createElement('StanPrzed');
            $stanPrzed->appendChild($dom->createTextNode((string) $this->stanPrzed));

            $faWiersz->appendChild($stanPrzed);
        }

        $dom->appendChild($faWiersz);

        return $dom;
    }
}
