<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_16;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_17;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_18;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_18A;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_23;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class Adnotacje extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    /**
     * @param P_16 $p_16 W przypadku dostawy towarów lub świadczenia usług, w odniesieniu do których obowiązek podatkowy powstaje zgodnie z art. 19a ust. 5 pkt 1 lub art. 21 ust. 1 ustawy - wyrazy "metoda kasowa", należy podać wartość "1"; w przeciwnym przypadku - wartość "2"
     * @param P_17 $p_17 W przypadku faktur, o których mowa w art. 106d ust. 1 ustawy - wyraz "samofakturowanie", należy podać wartość "1"; w przeciwnym przypadku - wartość "2"
     * @param P_18 $p_18 W przypadku dostawy towarów lub wykonania usługi, dla których obowiązanym do rozliczenia podatku od wartości dodanej lub podatku o podobnym charakterze jest nabywca towaru lub usługi - wyrazy "odwrotne obciążenie", należy podać wartość "1", w przeciwnym przypadku - wartość "2"
     * @param P_18A $p_18A W przypadku faktur, w których kwota należności ogółem przekracza kwotę 15 000 zł lub jej równowartość wyrażoną w walucie obcej, obejmujących dokonaną na rzecz podatnika dostawę towarów lub świadczenie usług, o których mowa w załączniku nr 15 do ustawy - wyrazy "mechanizm podzielonej płatności", przy czym do przeliczania na złote kwot wyrażonych w walucie obcej stosuje się zasady przeliczania kwot stosowane w celu określenia podstawy opodatkowania; należy podać wartość "1", w przeciwnym przypadku - wartość "2"
     * @param P_23 $p_23 W przypadku faktur wystawianych w procedurze uproszczonej przez drugiego w kolejności podatnika, o którym mowa w art. 135 ust. 1 pkt 4 lit. b i c oraz ust. 2, zawierającej adnotację, o której mowa w art. 136 ust. 1 pkt 1 i stwierdzenie, o którym mowa w art. 136 ust. 1 pkt 2 ustawy, należy podać wartość "1", w przeciwnym przypadku - wartość "2"
     */
    public function __construct(
        public readonly P_16 $p_16 = P_16::Default,
        public readonly P_17 $p_17 = P_17::Default,
        public readonly P_18 $p_18 = P_18::Default,
        public readonly P_18A $p_18A = P_18A::Default,
        public readonly Zwolnienie $zwolnienie = new Zwolnienie(),
        public readonly NoweSrodkiTransportu $noweSrodkiTransportu = new NoweSrodkiTransportu(),
        public readonly P_23 $p_23 = P_23::Default,
        public readonly PMarzy $pMarzy = new PMarzy(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $adnotacje = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Adnotacje');
        $dom->appendChild($adnotacje);

        $p_16 = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_16');
        $p_16->appendChild($dom->createTextNode((string) $this->p_16->value));

        $adnotacje->appendChild($p_16);

        $p_17 = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_17');
        $p_17->appendChild($dom->createTextNode((string) $this->p_17->value));

        $adnotacje->appendChild($p_17);

        $p_18 = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_18');
        $p_18->appendChild($dom->createTextNode((string) $this->p_18->value));

        $adnotacje->appendChild($p_18);

        $p_18A = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_18A');
        $p_18A->appendChild($dom->createTextNode((string) $this->p_18A->value));

        $adnotacje->appendChild($p_18A);

        $zwolnienie = $dom->importNode($this->zwolnienie->toDom()->documentElement, true);

        $adnotacje->appendChild($zwolnienie);

        $noweSrodkiTransportu = $dom->importNode($this->noweSrodkiTransportu->toDom()->documentElement, true);

        $adnotacje->appendChild($noweSrodkiTransportu);

        $p_23 = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_23');
        $p_23->appendChild($dom->createTextNode((string) $this->p_23->value));

        $adnotacje->appendChild($p_23);

        $pMarzy = $dom->importNode($this->pMarzy->toDom()->documentElement, true);

        $adnotacje->appendChild($pMarzy);

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        $data['Zwolnienie'] = Zwolnienie::normalizeXmlArray($data['Zwolnienie']);

        $data['NoweSrodkiTransportu'] = NoweSrodkiTransportu::normalizeXmlArray($data['NoweSrodkiTransportu']);

        $data['PMarzy'] = PMarzy::normalizeXmlArray($data['PMarzy']);

        return Arr::onlyClassParameters($data, self::class);
    }
}
