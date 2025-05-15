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
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\UU_ID;
use N1ebieski\KSEFClient\Requests\Online\ValueObjects\XmlNamespace;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class FaWiersz extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param UU_ID|null $uu_id Uniwersalny unikalny numer wiersza faktury
     * @param P_7|null $p_7 Nazwa (rodzaj) towaru lub usługi. Pole opcjonalne wyłącznie dla przypadku określonego w art 106j ust. 3 pkt 2 ustawy (faktura korygująca)
     * @param P_8A|null $p_8a Miara dostarczonych towarów lub zakres wykonanych usług. Pole opcjonalne dla przypadku określonego w art. 106e ust. 5 pkt 3 ustawy
     * @param P_8B|null $p_8b Ilość (liczba) dostarczonych towarów lub zakres wykonanych usług. Pole opcjonalne dla przypadku określonego w art. 106e ust. 5 pkt 3 ustawy
     * @param P_9A|null $p_9a Cena jednostkowa towaru lub usługi bez kwoty podatku (cena jednostkowa netto). Pole opcjonalne dla przypadków określonych w art. 106e ust. 2 i 3 oraz ust. 5 pkt 3 ustawy
     * @param P_11|null $p_11 Wartość dostarczonych towarów lub wykonanych usług, objętych transakcją, bez kwoty podatku (wartość sprzedaży netto). Pole opcjonalne dla przypadków określonych w art. 106e ust. 2 i 3 oraz ust. 5 pkt 3 ustawy
     * @param P_12|null $p_12 Stawka podatku. Pole opcjonalne dla przypadków określonych w art. 106e ust. 2, 3, ust. 4 pkt 3 i ust. 5 pkt 3 ustawy
     * @return void
     */
    public function __construct(
        public NrWierszaFa $nrWierszaFa,
        public ?UU_ID $uu_id = null,
        public ?P_7 $p_7 = null,
        public ?P_8A $p_8a = null,
        public ?P_8B $p_8b = null,
        public ?P_9A $p_9a = null,
        public ?P_11 $p_11 = null,
        public ?P_12 $p_12 = null
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $faWiersz = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'FaWiersz');
        $dom->appendChild($faWiersz);

        $nrWierszaFa = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'NrWierszaFa');
        $nrWierszaFa->appendChild($dom->createTextNode((string) $this->nrWierszaFa));

        $faWiersz->appendChild($nrWierszaFa);

        if ($this->uu_id instanceof UU_ID) {
            $uu_id = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'UU_ID');
            $uu_id->appendChild($dom->createTextNode((string) $this->uu_id));
            $faWiersz->appendChild($uu_id);
        }

        if ($this->p_7 instanceof P_7) {
            $p_7 = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'P_7');
            $p_7->appendChild($dom->createTextNode((string) $this->p_7));
            $faWiersz->appendChild($p_7);
        }

        if ($this->p_8a instanceof P_8A) {
            $p_8a = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'P_8A');
            $p_8a->appendChild($dom->createTextNode((string) $this->p_8a));
            $faWiersz->appendChild($p_8a);
        }

        if ($this->p_8b instanceof P_8B) {
            $p_8b = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'P_8B');
            $p_8b->appendChild($dom->createTextNode((string) $this->p_8b));
            $faWiersz->appendChild($p_8b);
        }

        if ($this->p_9a instanceof P_9A) {
            $p_9a = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'P_9A');
            $p_9a->appendChild($dom->createTextNode((string) $this->p_9a));
            $faWiersz->appendChild($p_9a);
        }

        if ($this->p_11 instanceof P_11) {
            $p_11 = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'P_11');
            $p_11->appendChild($dom->createTextNode((string) $this->p_11));
            $faWiersz->appendChild($p_11);
        }

        if ($this->p_12 instanceof P_12) {
            $p_12 = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'P_12');
            $p_12->appendChild($dom->createTextNode((string) $this->p_12->value));
            $faWiersz->appendChild($p_12);
        }

        $dom->appendChild($faWiersz);

        return $dom;
    }
}
