<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_13_2;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_14_2;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_14_2W;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;

final readonly class P_13_2Group extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param P_13_2 $p_13_2 Suma wartości sprzedaży netto objętej stawką obniżoną pierwszą - aktualnie 8 % albo 7%. W przypadku faktur zaliczkowych, kwota zaliczki netto. W przypadku faktur korygujących, kwota różnicy, o której mowa w art. 106j ust. 2 pkt 5 ustawy
     * @param P_14_2 $p_14_2 Kwota podatku od sumy wartości sprzedaży netto objętej stawką obniżoną pierwszą - aktualnie 8% albo 7%. W przypadku faktur zaliczkowych, kwota podatku wyliczona według wzoru, o którym mowa w art. 106f ust. 1 pkt 3 ustawy. W przypadku faktur korygujących, kwota różnicy, o której mowa w art. 106j ust. 2 pkt 5 ustawy
     * @param Optional|P_14_2W $p_14_2W W przypadku gdy faktura jest wystawiona w walucie obcej, kwota podatku od sumy wartości sprzedaży netto objętej stawką obniżoną, przeliczona zgodnie z przepisami Działu VI w związku z art. 106e ust. 11 ustawy - aktualnie 8% albo 7%. W przypadku faktur zaliczkowych, kwota podatku wyliczona według wzoru, o którym mowa w art. 106f ust. 1 pkt 3 ustawy. W przypadku faktur korygujących, kwota różnicy, o której mowa w art. 106j ust. 2 pkt 5 ustawy
     * @return void
     */
    public function __construct(
        public P_13_2 $p_13_2,
        public P_14_2 $p_14_2,
        public Optional | P_14_2W $p_14_2W = new Optional(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_13_2Group = $dom->createElement('P_13_2Group');
        $dom->appendChild($p_13_2Group);

        $p_13_2 = $dom->createElement('P_13_2');
        $p_13_2->appendChild($dom->createTextNode((string) $this->p_13_2));

        $p_13_2Group->appendChild($p_13_2);

        $p_14_2 = $dom->createElement('P_14_2');
        $p_14_2->appendChild($dom->createTextNode((string) $this->p_14_2));

        $p_13_2Group->appendChild($p_14_2);

        if ($this->p_14_2W instanceof P_14_2W) {
            $p_14_2W = $dom->createElement('P_14_2W');
            $p_14_2W->appendChild($dom->createTextNode((string) $this->p_14_2W));

            $p_13_2Group->appendChild($p_14_2W);
        }

        return $dom;
    }
}
