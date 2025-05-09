<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests;

use DateTimeImmutable;
use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\XmlSerializableInterface;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs\Adres;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs\Fa;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs\Naglowek;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs\Platnosc;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs\Podmiot1;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs\Podmiot2;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs\Stopka;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs\WarunkiTransakcji;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\AdresL2;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\FP;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\NrKlienta;
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
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_19N;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_1M;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_22N;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_PMarzyN;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\SystemInfo;
use N1ebieski\KSEFClient\Resources\Request;
use N1ebieski\KSEFClient\Support\Concerns\HasToXml;
use RuntimeException;

final readonly class SendRequest extends Request implements XmlSerializableInterface
{
    use HasToXml;

    /**
     * @param Podmiot1 $podmiot1 Dane podatnika. Imię i nazwisko lub nazwa sprzedawcy towarów lub usług
     * @param Podmiot2 $podmiot2 Dane nabywcy
     * @param Fa $fa Na podstawie art. 106a - 106q ustawy. Pola dotyczące wartości sprzedaży i podatku wypełnia się w walucie, w której wystawiono fakturę, z wyjątkiem pól dotyczących podatku przeliczonego zgodnie z przepisami Działu VI w związku z art. 106e ust. 11 ustawy. W przypadku wystawienia faktury korygującej, wypełnia się wszystkie pola wg stanu po korekcie, a pola dotyczące podstaw opodatkowania, podatku oraz należności ogółem wypełnia się poprzez różnicę
     * @param null|Stopka $stopka Pozostałe dane na fakturze
     * @return void
     */
    public function __construct(
        public Naglowek $naglowek,
        public Podmiot1 $podmiot1,
        public Podmiot2 $podmiot2,
        public Fa $fa,
        // public ?Stopka $stopka = null
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $faktura = $dom->createElement('Faktura');
        $faktura->setAttribute('xmlns', 'http://crd.gov.pl/wzor/2023/06/29/12648/');
        $faktura->setAttribute('xmlns:xsd', 'http://www.w3.org/2001/XMLSchema');
        $faktura->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');

        $dom->appendChild($faktura);

        $naglowek = $dom->importNode($this->naglowek->toDom()->documentElement, true);

        $faktura->appendChild($naglowek);

        $podmiot1 = $dom->importNode($this->podmiot1->toDom()->documentElement, true);

        $faktura->appendChild($podmiot1);

        $podmiot2 = $dom->importNode($this->podmiot2->toDom()->documentElement, true);

        $faktura->appendChild($podmiot2);

        $fa = $dom->importNode($this->fa->toDom()->documentElement, true);

        $faktura->appendChild($fa);

        return $dom;
    }
}
