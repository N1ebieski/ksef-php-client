<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_13_3;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_14_3;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_14_3W;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;

final readonly class P_13_3Group extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param P_13_3 $p_13_3 Suma wartości sprzedaży netto objętej stawką obniżoną drugą - aktualnie 5%. W przypadku faktur zaliczkowych, kwota zaliczki netto. W przypadku faktur korygujących, kwota różnicy, o której mowa w art. 106j ust. 2 pkt 5 ustawy
     * @param P_14_3 $p_14_3 Kwota podatku od sumy wartości sprzedaży netto objętej stawką obniżoną drugą - aktualnie 5%. W przypadku faktur zaliczkowych, kwota podatku wyliczona według wzoru, o którym mowa w art. 106f ust. 1 pkt 3 ustawy. W przypadku faktur korygujących, kwota różnicy, o której mowa w art. 106j ust. 2 pkt 5 ustawy
     * @param Optional|P_14_3W $p_14_3w W przypadku gdy faktura jest wystawiona w walucie obcej, kwota podatku od sumy wartości sprzedaży netto objętej stawką obniżoną drugą, przeliczona zgodnie z przepisami Działu VI w związku z art. 106e ust. 11 ustawy - aktualnie 5%. W przypadku faktur zaliczkowych, kwota podatku wyliczona według wzoru, o którym mowa w art. 106f ust. 1 pkt 3 ustawy. W przypadku faktur korygujących, kwota różnicy, o której mowa w art. 106j ust. 2 pkt 5 ustawy
     * @return void
     */
    public function __construct(
        public P_13_3 $p_13_3,
        public P_14_3 $p_14_3,
        public Optional | P_14_3W $p_14_3w = new Optional(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_13_3group = $dom->createElement('P_13_3Group');
        $dom->appendChild($p_13_3group);

        $p_13_3 = $dom->createElement('P_13_3');
        $p_13_3->appendChild($dom->createTextNode((string) $this->p_13_3));

        $p_13_3group->appendChild($p_13_3);

        $p_14_3 = $dom->createElement('P_14_3');
        $p_14_3->appendChild($dom->createTextNode((string) $this->p_14_3));

        $p_13_3group->appendChild($p_14_3);

        if ($this->p_14_3w instanceof P_14_3W) {
            $p_14_3w = $dom->createElement('P_14_3W');
            $p_14_3w->appendChild($dom->createTextNode((string) $this->p_14_3w));

            $p_13_3group->appendChild($p_14_3w);
        }

        return $dom;
    }
}
