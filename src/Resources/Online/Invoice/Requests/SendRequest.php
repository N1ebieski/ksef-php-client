<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests;

use DateTimeImmutable;
use DOMDocument;
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
use RuntimeException;

final readonly class SendRequest extends Request implements XmlSerializableInterface
{
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

    public function toXml(): string
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $faktura = $dom->createElement('Faktura');
        $faktura->setAttribute('xmlns', 'http://crd.gov.pl/wzor/2023/06/29/12648/');
        $faktura->setAttribute('xmlns:xsd', 'http://www.w3.org/2001/XMLSchema');
        $faktura->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');

        $dom->appendChild($faktura);

        $naglowek = $dom->createElement('Naglowek');
        $faktura->appendChild($naglowek);

        $kodFormularza = $dom->createElement('KodFormularza');
        $kodFormularza->setAttribute('kodSystemowy', (string) $this->naglowek->wariantFormularza->value);
        $kodFormularza->setAttribute('wersjaSchemy', $this->naglowek->wariantFormularza->getSchemaVersion());
        $kodFormularza->appendChild($dom->createTextNode('FA'));

        $naglowek->appendChild($kodFormularza);

        $wariantFormularza = $dom->createElement('WariantFormularza');
        $wariantFormularza->appendChild($dom->createTextNode($this->naglowek->wariantFormularza->getWariantFormularza()));

        $naglowek->appendChild($wariantFormularza);

        $dataWytworzeniaFa = $dom->createElement('DataWytworzeniaFa');
        $dataWytworzeniaFa->appendChild($dom->createTextNode(new DateTimeImmutable()->format('Y-m-d\TH:i:s\Z')));

        $naglowek->appendChild($dataWytworzeniaFa);

        if ($this->naglowek->systemInfo instanceof SystemInfo) {
            $systemInfo = $dom->createElement('SystemInfo');
            $systemInfo->appendChild($dom->createTextNode((string) $this->naglowek->systemInfo));
            $naglowek->appendChild($systemInfo);
        }

        $podmiot1 = $dom->createElement('Podmiot1');
        $faktura->appendChild($podmiot1);

        $daneIdentyfikacyjne = $dom->createElement('DaneIdentyfikacyjne');
        $podmiot1->appendChild($daneIdentyfikacyjne);

        $nip = $dom->createElement('NIP');
        $nip->appendChild($dom->createTextNode((string) $this->podmiot1->daneIdentyfikacyjne->nip));

        $daneIdentyfikacyjne->appendChild($nip);

        $nazwa = $dom->createElement('Nazwa');
        $nazwa->appendChild($dom->createTextNode((string) $this->podmiot1->daneIdentyfikacyjne->nazwa));

        $daneIdentyfikacyjne->appendChild($nazwa);

        $adres = $dom->createElement('Adres');
        $podmiot1->appendChild($adres);

        $kodKraju = $dom->createElement('KodKraju');
        $kodKraju->appendChild($dom->createTextNode((string) $this->podmiot1->adres->kodKraju));

        $adres->appendChild($kodKraju);

        $adresL1 = $dom->createElement('AdresL1');
        $adresL1->appendChild($dom->createTextNode((string) $this->podmiot1->adres->adresL1));

        $adres->appendChild($adresL1);

        if ($this->podmiot1->adres->adresL2 instanceof AdresL2) {
            $adresL2 = $dom->createElement('AdresL2');
            $adresL2->appendChild($dom->createTextNode((string) $this->podmiot1->adres->adresL2));
            $adres->appendChild($adresL2);
        }

        foreach ($this->podmiot1->daneKontaktowe as $_daneKontaktowe) {
            $daneKontaktowe = $dom->createElement('DaneKontaktowe');
            $podmiot1->appendChild($daneKontaktowe);

            if ($_daneKontaktowe->email !== null) {
                $email = $dom->createElement('Email');
                $email->appendChild($dom->createTextNode((string) $_daneKontaktowe->email));
                $daneKontaktowe->appendChild($email);
            }

            if ($_daneKontaktowe->telefon !== null) {
                $telefon = $dom->createElement('Telefon');
                $telefon->appendChild($dom->createTextNode((string) $_daneKontaktowe->telefon));
                $daneKontaktowe->appendChild($telefon);
            }
        }

        $podmiot2 = $dom->createElement('Podmiot2');
        $faktura->appendChild($podmiot2);

        $daneIdentyfikacyjne = $dom->createElement('DaneIdentyfikacyjne');
        $podmiot2->appendChild($daneIdentyfikacyjne);

        $nip = $dom->createElement('NIP');
        $nip->appendChild($dom->createTextNode((string) $this->podmiot2->daneIdentyfikacyjne->nip));

        $daneIdentyfikacyjne->appendChild($nip);

        $nazwa = $dom->createElement('Nazwa');
        $nazwa->appendChild($dom->createTextNode((string) $this->podmiot2->daneIdentyfikacyjne->nazwa));

        $daneIdentyfikacyjne->appendChild($nazwa);

        if ($this->podmiot2->adres instanceof Adres) {
            $adres = $dom->createElement('Adres');
            $podmiot2->appendChild($adres);

            $kodKraju = $dom->createElement('KodKraju');
            $kodKraju->appendChild($dom->createTextNode((string) $this->podmiot2->adres->kodKraju));
            $adres->appendChild($kodKraju);

            $adresL1 = $dom->createElement('AdresL1');
            $adresL1->appendChild($dom->createTextNode((string) $this->podmiot2->adres->adresL1));
            $adres->appendChild($adresL1);

            if ($this->podmiot2->adres->adresL2 instanceof AdresL2) {
                $adresL2 = $dom->createElement('AdresL2');
                $adresL2->appendChild($dom->createTextNode((string) $this->podmiot2->adres->adresL2));
                $adres->appendChild($adresL2);
            }
        }

        foreach ($this->podmiot2->daneKontaktowe as $_daneKontaktowe) {
            $daneKontaktowe = $dom->createElement('DaneKontaktowe');
            $podmiot2->appendChild($daneKontaktowe);

            if ($_daneKontaktowe->email !== null) {
                $email = $dom->createElement('Email');
                $email->appendChild($dom->createTextNode((string) $_daneKontaktowe->email));
                $daneKontaktowe->appendChild($email);
            }

            if ($_daneKontaktowe->telefon !== null) {
                $telefon = $dom->createElement('Telefon');
                $telefon->appendChild($dom->createTextNode((string) $_daneKontaktowe->telefon));
                $daneKontaktowe->appendChild($telefon);
            }
        }

        if ($this->podmiot2->nrKlienta instanceof NrKlienta) {
            $nrKlienta = $dom->createElement('NrKlienta');
            $nrKlienta->appendChild($dom->createTextNode((string) $this->podmiot2->nrKlienta));
            $podmiot2->appendChild($nrKlienta);
        }

        $fa = $dom->createElement('Fa');
        $faktura->appendChild($fa);

        $kodWaluty = $dom->createElement('KodWaluty');
        $kodWaluty->appendChild($dom->createTextNode((string) $this->fa->kodWaluty));

        $fa->appendChild($kodWaluty);

        $p1 = $dom->createElement('P_1');
        $p1->appendChild($dom->createTextNode((string) $this->fa->p_1));

        $fa->appendChild($p1);

        if ($this->fa->p_1m instanceof P_1M) {
            $p1M = $dom->createElement('P_1M');
            $p1M->appendChild($dom->createTextNode((string) $this->fa->p_1m));
            $fa->appendChild($p1M);
        }

        $p2 = $dom->createElement('P_2');
        $p2->appendChild($dom->createTextNode((string) $this->fa->p_2));

        $fa->appendChild($p2);

        $p6 = $dom->createElement('P_6');
        $p6->appendChild($dom->createTextNode((string) $this->fa->p_6));

        $fa->appendChild($p6);

        if ($this->fa->p_13_1 instanceof P_13_1) {
            $p13_1 = $dom->createElement('P_13_1');
            $p13_1->appendChild($dom->createTextNode((string) $this->fa->p_13_1));

            $fa->appendChild($p13_1);
        }

        if ($this->fa->p_14_1 instanceof P_14_1) {
            $p14_1 = $dom->createElement('P_14_1');
            $p14_1->appendChild($dom->createTextNode((string) $this->fa->p_14_1));

            $fa->appendChild($p14_1);
        }

        if ($this->fa->p_13_2 instanceof P_13_2) {
            $p13_2 = $dom->createElement('P_13_2');
            $p13_2->appendChild($dom->createTextNode((string) $this->fa->p_13_2));

            $fa->appendChild($p13_2);
        }

        if ($this->fa->p_13_3 instanceof P_13_3) {
            $p13_3 = $dom->createElement('P_13_3');
            $p13_3->appendChild($dom->createTextNode((string) $this->fa->p_13_3));

            $fa->appendChild($p13_3);
        }

        if ($this->fa->p_14_3 instanceof P_14_3) {
            $p14_3 = $dom->createElement('P_14_3');
            $p14_3->appendChild($dom->createTextNode((string) $this->fa->p_14_3));

            $fa->appendChild($p14_3);
        }

        if ($this->fa->p_13_4 instanceof P_13_4) {
            $p13_4 = $dom->createElement('P_13_4');
            $p13_4->appendChild($dom->createTextNode((string) $this->fa->p_13_4));

            $fa->appendChild($p13_4);
        }

        if ($this->fa->p_13_5 instanceof P_13_5) {
            $p13_5 = $dom->createElement('P_13_5');
            $p13_5->appendChild($dom->createTextNode((string) $this->fa->p_13_5));

            $fa->appendChild($p13_5);
        }

        if ($this->fa->p_13_7 instanceof P_13_7) {
            $p13_7 = $dom->createElement('P_13_7');
            $p13_7->appendChild($dom->createTextNode((string) $this->fa->p_13_7));

            $fa->appendChild($p13_7);
        }

        if ($this->fa->p_14_2 instanceof P_14_2) {
            $p14_2 = $dom->createElement('P_14_2');
            $p14_2->appendChild($dom->createTextNode((string) $this->fa->p_14_2));

            $fa->appendChild($p14_2);
        }

        if ($this->fa->p_14_4 instanceof P_14_4) {
            $p14_4 = $dom->createElement('P_14_4');
            $p14_4->appendChild($dom->createTextNode((string) $this->fa->p_14_4));

            $fa->appendChild($p14_4);
        }

        $p15 = $dom->createElement('P_15');
        $p15->appendChild($dom->createTextNode((string) $this->fa->p_15));

        $fa->appendChild($p15);

        $adnotacje = $dom->createElement('Adnotacje');
        $fa->appendChild($adnotacje);

        $p16 = $dom->createElement('P_16');
        $p16->appendChild($dom->createTextNode((string) $this->fa->adnotacje->p_16->value));

        $adnotacje->appendChild($p16);

        $p17 = $dom->createElement('P_17');
        $p17->appendChild($dom->createTextNode((string) $this->fa->adnotacje->p_17->value));

        $adnotacje->appendChild($p17);

        $p18 = $dom->createElement('P_18');
        $p18->appendChild($dom->createTextNode((string) $this->fa->adnotacje->p_18->value));

        $adnotacje->appendChild($p18);

        $p18A = $dom->createElement('P_18A');
        $p18A->appendChild($dom->createTextNode((string) $this->fa->adnotacje->p_18a->value));

        $adnotacje->appendChild($p18A);

        $zwolnienie = $dom->createElement('Zwolnienie');
        $adnotacje->appendChild($zwolnienie);

        if ($this->fa->adnotacje->zwolnienie->p_19N instanceof P_19N) {
            $p19N = $dom->createElement('P_19N');
            $p19N->appendChild($dom->createTextNode((string) $this->fa->adnotacje->zwolnienie->p_19N->value));
            $zwolnienie->appendChild($p19N);
        }

        $noweSrodkiTransportu = $dom->createElement('NoweSrodkiTransportu');
        $adnotacje->appendChild($noweSrodkiTransportu);

        if ($this->fa->adnotacje->noweSrodkiTransportu->p_22N instanceof P_22N) {
            $p22N = $dom->createElement('P_22N');
            $p22N->appendChild($dom->createTextNode((string) $this->fa->adnotacje->noweSrodkiTransportu->p_22N->value));
            $noweSrodkiTransportu->appendChild($p22N);
        }

        $p23 = $dom->createElement('P_23');
        $p23->appendChild($dom->createTextNode((string) $this->fa->adnotacje->p_23->value));

        $adnotacje->appendChild($p23);

        $pPMarzy = $dom->createElement('PMarzy');
        $adnotacje->appendChild($pPMarzy);

        if ($this->fa->adnotacje->pMarzy->p_pMarzyN instanceof P_PMarzyN) {
            $pPMarzyN = $dom->createElement('P_PMarzyN');
            $pPMarzyN->appendChild($dom->createTextNode((string) $this->fa->adnotacje->pMarzy->p_pMarzyN->value));
            $pPMarzy->appendChild($pPMarzyN);
        }

        $rodzajFaktury = $dom->createElement('RodzajFaktury');
        $rodzajFaktury->appendChild($dom->createTextNode((string) $this->fa->rodzajFaktury->value));

        $fa->appendChild($rodzajFaktury);

        if ($this->fa->fP instanceof FP) {
            $fP = $dom->createElement('FP');
            $fP->appendChild($dom->createTextNode((string) $this->fa->fP->value));
            $fa->appendChild($fP);
        }

        foreach ($this->fa->dodatkowyOpis as $_dodatkowyOpis) {
            $dodatkowyOpis = $dom->createElement('DodatkowyOpis');
            $fa->appendChild($dodatkowyOpis);

            $klucz = $dom->createElement('Klucz');
            $klucz->appendChild($dom->createTextNode((string) $_dodatkowyOpis->klucz));
            $dodatkowyOpis->appendChild($klucz);

            $wartosc = $dom->createElement('Wartosc');
            $wartosc->appendChild($dom->createTextNode((string) $_dodatkowyOpis->wartosc));
            $dodatkowyOpis->appendChild($wartosc);

            if ($_dodatkowyOpis->nrWiersza !== null) {
                $nrWiersza = $dom->createElement('NrWiersza');
                $nrWiersza->appendChild($dom->createTextNode((string) $_dodatkowyOpis->nrWiersza->value));
                $dodatkowyOpis->appendChild($nrWiersza);
            }
        }

        foreach ($this->fa->faWiersz as $index => $_faWiersz) {
            $faWiersz = $dom->createElement('FaWiersz');
            $fa->appendChild($faWiersz);

            $nrWierszaFa = $dom->createElement('NrWierszaFa');
            $nrWierszaFa->appendChild($dom->createTextNode((string) ($index + 1)));
            $faWiersz->appendChild($nrWierszaFa);

            if ($_faWiersz->uu_id !== null) {
                $uuID = $dom->createElement('UU_ID');
                $uuID->appendChild($dom->createTextNode((string) $_faWiersz->uu_id));
                $faWiersz->appendChild($uuID);
            }

            if ($_faWiersz->p_7 !== null) {
                $p7 = $dom->createElement('P_7');
                $p7->appendChild($dom->createTextNode((string) $_faWiersz->p_7));
                $faWiersz->appendChild($p7);
            }

            if ($_faWiersz->p_8a !== null) {
                $p8A = $dom->createElement('P_8A');
                $p8A->appendChild($dom->createTextNode((string) $_faWiersz->p_8a));
                $faWiersz->appendChild($p8A);
            }

            if ($_faWiersz->p_8b !== null) {
                $p8B = $dom->createElement('P_8B');
                $p8B->appendChild($dom->createTextNode((string) $_faWiersz->p_8b));
                $faWiersz->appendChild($p8B);
            }

            if ($_faWiersz->p_9a !== null) {
                $p9A = $dom->createElement('P_9A');
                $p9A->appendChild($dom->createTextNode((string) $_faWiersz->p_9a));
                $faWiersz->appendChild($p9A);
            }

            if ($_faWiersz->p_11 !== null) {
                $p11 = $dom->createElement('P_11');
                $p11->appendChild($dom->createTextNode((string) $_faWiersz->p_11));
                $faWiersz->appendChild($p11);
            }

            if ($_faWiersz->p_12 !== null) {
                $p12 = $dom->createElement('P_12');
                $p12->appendChild($dom->createTextNode((string) $_faWiersz->p_12->value));
                $faWiersz->appendChild($p12);
            }
        }

        if ($this->fa->platnosc instanceof Platnosc) {
            $platnosc = $dom->createElement('Platnosc');
            $fa->appendChild($platnosc);

            $zaplacono = $dom->createElement('Zaplacono');
            $zaplacono->appendChild($dom->createTextNode((string) $this->fa->platnosc->zaplacono->value));
            $platnosc->appendChild($zaplacono);

            $dataZaplaty = $dom->createElement('DataZaplaty');
            $dataZaplaty->appendChild($dom->createTextNode((string) $this->fa->platnosc->dataZaplaty));
            $platnosc->appendChild($dataZaplaty);

            $formaPlatnosci = $dom->createElement('FormaPlatnosci');
            $formaPlatnosci->appendChild($dom->createTextNode((string) $this->fa->platnosc->formaPlatnosci->value));
            $platnosc->appendChild($formaPlatnosci);
        }

        if ($this->fa->warunkiTransakcji instanceof WarunkiTransakcji) {
            $warunkiTransakcji = $dom->createElement('WarunkiTransakcji');
            $fa->appendChild($warunkiTransakcji);

            foreach ($this->fa->warunkiTransakcji->zamowienia as $_zamowienia) {
                $zamowienia = $dom->createElement('Zamowienia');
                $warunkiTransakcji->appendChild($zamowienia);

                if ($_zamowienia->dataZamowienia !== null) {
                    $dataZamowienia = $dom->createElement('DataZamowienia');
                    $dataZamowienia->appendChild($dom->createTextNode((string) $_zamowienia->dataZamowienia));
                    $zamowienia->appendChild($dataZamowienia);
                }

                if ($_zamowienia->nrZamowienia !== null) {
                    $nrZamowienia = $dom->createElement('NrZamowienia');
                    $nrZamowienia->appendChild($dom->createTextNode((string) $_zamowienia->nrZamowienia));
                    $zamowienia->appendChild($nrZamowienia);
                }
            }
        }

        if ($this->stopka instanceof Stopka) {
            $stopka = $dom->createElement('Stopka');
            $naglowek->appendChild($stopka);

            foreach ($this->stopka->informacje as $_informacje) {
                $informacje = $dom->createElement('Informacje');
                $stopka->appendChild($informacje);

                if ($_informacje->stopkaFaktury !== null) {
                    $stopkaFaktury = $dom->createElement('StopkaFaktury');
                    $stopkaFaktury->appendChild($dom->createTextNode((string) $_informacje->stopkaFaktury));
                    $informacje->appendChild($stopkaFaktury);
                }
            }

            foreach ($this->stopka->rejestry as $_rejestry) {
                $rejestry = $dom->createElement('Rejestry');
                $stopka->appendChild($rejestry);

                if ($_rejestry->krs !== null) {
                    $krs = $dom->createElement('KRS');
                    $krs->appendChild($dom->createTextNode((string) $_rejestry->krs));
                    $rejestry->appendChild($krs);
                }

                if ($_rejestry->regon !== null) {
                    $regon = $dom->createElement('REGON');
                    $regon->appendChild($dom->createTextNode((string) $_rejestry->regon));
                    $rejestry->appendChild($regon);
                }

                if ($_rejestry->bdo !== null) {
                    $bdo = $dom->createElement('BDO');
                    $bdo->appendChild($dom->createTextNode((string) $_rejestry->bdo));
                    $rejestry->appendChild($bdo);
                }
            }
        }

        $xml = $dom->saveXML();

        return $xml ?: throw new RuntimeException('Unable to save XML');
    }
}
