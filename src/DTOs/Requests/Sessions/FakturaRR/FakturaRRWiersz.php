<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions\FakturaRR;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\XmlNormalizableInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\CN;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\FakturaRR\P_10;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\FakturaRR\P_11;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\FakturaRR\P_4AA;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\FakturaRR\P_5;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\FakturaRR\P_6A;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\FakturaRR\P_6B;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\FakturaRR\P_6C;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\FakturaRR\P_7;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\FakturaRR\P_8;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\FakturaRR\P_9;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\GTIN;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\KursWaluty;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\NrWierszaFa;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\PKWiU;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\StanPrzed;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\UU_ID;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class FakturaRRWiersz extends AbstractDTO implements DomSerializableInterface, XmlNormalizableInterface
{
    /**
     * @param P_5 $p_5 Nazwa nabytego produktu rolnego lub nabytej usługi rolniczej.
     * @param P_6A $p_6A Jednostka miary nabytego produktu rolnego lub nabytej usługi rolniczej.
     * @param P_6B $p_6B Ilość nabytego produktu rolnego lub nabytej usługi rolniczej.
     * @param P_6C $p_6C Oznaczenie (opis) klasy lub jakości nabytego produktu rolnego lub nabytej usługi rolniczej.
     * @param P_7 $p_7 Cena jednostkowa nabytego produktu rolnego lub nabytej usługi rolniczej bez kwoty zryczałtowanego zwrotu podatku.
     * @param P_8 $p_8 Wartość nabytego produktu rolnego lub nabytej usługi rolniczej bez kwoty zryczałtowanego zwrotu podatku.
     * @param P_9 $p_9 Stawka zryczałtowanego zwrotu podatku.
     * @param P_10 $p_10 Kwota zryczałtowanego zwrotu podatku od wartości nabytego produktu rolnego lub nabytej usługi rolniczej.
     * @param P_11 $p_11 Wartość nabytego produktu rolnego lub nabytej usługi rolniczej wraz z kwotą zryczałtowanego zwrotu podatku.
     * @param Optional|UU_ID $uu_id Uniwersalny unikalny numer wiersza faktury VAT RR/faktury VAT RR KOREKTA
     * @param Optional|P_4AA $p_4AA Data dokonania nabycia. Pole wypełnia się w przypadku, gdy dla poszczególnych pozycji faktury VAT RR/ faktury VAT RR KOREKTA występują różne daty dokonania nabycia. W przeciwnym przypadku pole pomija się.
     * @param Optional|GTIN $gtin Globalny numer jednostki handlowej.
     * @param Optional|PKWiU $pkwiu Symbol Polskiej Klasyfikacji Wyrobów i Usług
     * @param Optional|CN $cn Symbol Nomenklatury Scalonej
     */
    public function __construct(
        public readonly NrWierszaFa $nrWierszaFa,
        public readonly P_5 $p_5,
        public readonly P_6A $p_6A,
        public readonly P_6B $p_6B,
        public readonly P_6C $p_6C,
        public readonly P_7 $p_7,
        public readonly P_8 $p_8,
        public readonly P_9 $p_9,
        public readonly P_10 $p_10,
        public readonly P_11 $p_11,
        public readonly Optional | UU_ID $uu_id = new Optional(),
        public readonly Optional | P_4AA $p_4AA = new Optional(),
        public readonly Optional | GTIN $gtin = new Optional(),
        public readonly Optional | PKWiU $pkwiu = new Optional(),
        public readonly Optional | CN $cn = new Optional(),
        public readonly Optional | StanPrzed $stanPrzed = new Optional(),
        public readonly Optional | KursWaluty $kursWaluty = new Optional(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $fakturaRRWiersz = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'FakturaRRWiersz');
        $dom->appendChild($fakturaRRWiersz);

        $nrWierszaFa = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'NrWierszaFa');
        $nrWierszaFa->appendChild($dom->createTextNode((string) $this->nrWierszaFa));

        $fakturaRRWiersz->appendChild($nrWierszaFa);

        if ($this->uu_id instanceof UU_ID) {
            $uu_ID = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'UU_ID');
            $uu_ID->appendChild($dom->createTextNode((string) $this->uu_id));
            $fakturaRRWiersz->appendChild($uu_ID);
        }

        if ($this->p_4AA instanceof P_4AA) {
            $p_4AA = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'P_4AA');
            $p_4AA->appendChild($dom->createTextNode((string) $this->p_4AA));
            $fakturaRRWiersz->appendChild($p_4AA);
        }

        $p_5 = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'P_5');
        $p_5->appendChild($dom->createTextNode((string) $this->p_5));

        $fakturaRRWiersz->appendChild($p_5);

        if ($this->gtin instanceof GTIN) {
            $gTIN = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'GTIN');
            $gTIN->appendChild($dom->createTextNode((string) $this->gtin));
            $fakturaRRWiersz->appendChild($gTIN);
        }

        if ($this->pkwiu instanceof PKWiU) {
            $pKWiU = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'PKWiU');
            $pKWiU->appendChild($dom->createTextNode((string) $this->pkwiu));
            $fakturaRRWiersz->appendChild($pKWiU);
        }

        if ($this->cn instanceof CN) {
            $cN = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'CN');
            $cN->appendChild($dom->createTextNode((string) $this->cn));
            $fakturaRRWiersz->appendChild($cN);
        }

        $p_6A = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'P_6A');
        $p_6A->appendChild($dom->createTextNode((string) $this->p_6A));

        $fakturaRRWiersz->appendChild($p_6A);

        $p_6B = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'P_6B');
        $p_6B->appendChild($dom->createTextNode((string) $this->p_6B));

        $fakturaRRWiersz->appendChild($p_6B);

        $p_6C = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'P_6C');
        $p_6C->appendChild($dom->createTextNode((string) $this->p_6C));

        $fakturaRRWiersz->appendChild($p_6C);

        $p_7 = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'P_7');
        $p_7->appendChild($dom->createTextNode((string) $this->p_7));

        $fakturaRRWiersz->appendChild($p_7);

        $p_8 = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'P_8');
        $p_8->appendChild($dom->createTextNode((string) $this->p_8));

        $fakturaRRWiersz->appendChild($p_8);

        $p_9 = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'P_9');
        $p_9->appendChild($dom->createTextNode((string) $this->p_9->value));

        $fakturaRRWiersz->appendChild($p_9);

        $p_10 = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'P_10');
        $p_10->appendChild($dom->createTextNode((string) $this->p_10));

        $fakturaRRWiersz->appendChild($p_10);

        $p_11 = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'P_11');
        $p_11->appendChild($dom->createTextNode((string) $this->p_11));

        $fakturaRRWiersz->appendChild($p_11);

        if ($this->stanPrzed instanceof StanPrzed) {
            $stanPrzed = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'StanPrzed');
            $stanPrzed->appendChild($dom->createTextNode((string) $this->stanPrzed->value));
            $fakturaRRWiersz->appendChild($stanPrzed);
        }

        if ($this->kursWaluty instanceof KursWaluty) {
            $kursWaluty = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'KursWaluty');
            $kursWaluty->appendChild($dom->createTextNode((string) $this->kursWaluty));
            $fakturaRRWiersz->appendChild($kursWaluty);
        }

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        $data['NrWierszaFa'] = (int) $data['NrWierszaFa']; //@phpstan-ignore-line cast.int
        $data['uu_id'] = $data['UU_ID'] ?? new Optional();
        $data['gtin'] = $data['GTIN'] ?? new Optional();
        $data['pkwiu'] = $data['PKWiU'] ?? new Optional();
        $data['cn'] = $data['CN'] ?? new Optional();

        return Arr::only($data, ['NrWierszaFa', 'P_5', 'P_6A', 'P_6B', 'P_6C', 'P_7', 'P_8', 'P_9', 'P_10', 'P_11', 'uu_id', 'P_4AA', 'gtin', 'pkwiu', 'cn', 'StanPrzed', 'KursWaluty']);
    }
}
