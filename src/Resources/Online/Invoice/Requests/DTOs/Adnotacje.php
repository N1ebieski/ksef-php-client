<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_16;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_17;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_18;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_18A;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_19N;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_22N;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_23;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_PMarzyN;
use N1ebieski\KSEFClient\Support\DTO;

final readonly class Adnotacje extends DTO implements DomSerializableInterface
{
    /**
     * @param P_16 $p_16 W przypadku dostawy towarów lub świadczenia usług, w odniesieniu do których obowiązek podatkowy powstaje zgodnie z art. 19a ust. 5 pkt 1 lub art. 21 ust. 1 ustawy - wyrazy "metoda kasowa", należy podać wartość "1"; w przeciwnym przypadku - wartość "2"
     * @param P_17 $p_17 W przypadku faktur, o których mowa w art. 106d ust. 1 ustawy - wyraz "samofakturowanie", należy podać wartość "1"; w przeciwnym przypadku - wartość "2"
     * @param P_18 $p_18 W przypadku dostawy towarów lub wykonania usługi, dla których obowiązanym do rozliczenia podatku od wartości dodanej lub podatku o podobnym charakterze jest nabywca towaru lub usługi - wyrazy "odwrotne obciążenie", należy podać wartość "1", w przeciwnym przypadku - wartość "2"
     * @param P_18A $p_18a W przypadku faktur, w których kwota należności ogółem przekracza kwotę 15 000 zł lub jej równowartość wyrażoną w walucie obcej, obejmujących dokonaną na rzecz podatnika dostawę towarów lub świadczenie usług, o których mowa w załączniku nr 15 do ustawy - wyrazy "mechanizm podzielonej płatności", przy czym do przeliczania na złote kwot wyrażonych w walucie obcej stosuje się zasady przeliczania kwot stosowane w celu określenia podstawy opodatkowania; należy podać wartość "1", w przeciwnym przypadku - wartość "2"
     * @param P_23 $p_23 W przypadku faktur wystawianych w procedurze uproszczonej przez drugiego w kolejności podatnika, o którym mowa w art. 135 ust. 1 pkt 4 lit. b i c oraz ust. 2, zawierającej adnotację, o której mowa w art. 136 ust. 1 pkt 1 i stwierdzenie, o którym mowa w art. 136 ust. 1 pkt 2 ustawy, należy podać wartość "1", w przeciwnym przypadku - wartość "2"
     * @return void
     */
    public function __construct(
        public P_16 $p_16 = P_16::Default,
        public P_17 $p_17 = P_17::Default,
        public P_18 $p_18 = P_18::Default,
        public P_18A $p_18a = P_18A::Default,
        public Zwolnienie $zwolnienie = new Zwolnienie(),
        public NoweSrodkiTransportu $noweSrodkiTransportu = new NoweSrodkiTransportu(),
        public P_23 $p_23 = P_23::Default,
        public PMarzy $pMarzy = new PMarzy(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $adnotacje = $dom->createElement('Adnotacje');
        $dom->appendChild($adnotacje);

        $p_16 = $dom->createElement('P_16');
        $p_16->appendChild($dom->createTextNode((string) $this->p_16));

        $adnotacje->appendChild($p_16);

        $p_17 = $dom->createElement('P_17');
        $p_17->appendChild($dom->createTextNode((string) $this->p_17));

        $adnotacje->appendChild($p_17);

        $p_18 = $dom->createElement('P_18');
        $p_18->appendChild($dom->createTextNode((string) $this->p_18));

        $adnotacje->appendChild($p_18);

        $p_23 = $dom->createElement('P_18A');
        $p_23->appendChild($dom->createTextNode((string) $this->p_18a));

        $adnotacje->appendChild($p_23);

        $zwolnienie = $dom->importNode($this->zwolnienie->toDom()->documentElement, true);

        $adnotacje->appendChild($zwolnienie);

        $noweSrodkiTransportu = $dom->importNode($this->noweSrodkiTransportu->toDom()->documentElement, true);

        $adnotacje->appendChild($noweSrodkiTransportu);

        $p_23 = $dom->createElement('P_23');
        $p_23->appendChild($dom->createTextNode((string) $this->p_23));

        $adnotacje->appendChild($p_23);

        $pMarzy = $dom->importNode($this->pMarzy->toDom()->documentElement, true);

        $adnotacje->appendChild($pMarzy);

        return $dom;
    }
}
