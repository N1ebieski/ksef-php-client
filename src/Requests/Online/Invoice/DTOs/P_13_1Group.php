<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_13_1;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_14_1;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_14_1W;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;

final readonly class P_13_1Group extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param P_13_1 $p_13_1 Suma wartości sprzedaży netto ze stawką podstawową - aktualnie 23% albo 22%. W przypadku faktur zaliczkowych, kwota zaliczki netto. W przypadku faktur korygujących, kwota różnicy, o której mowa w art. 106j ust. 2 pkt 5 ustawy
     * @param P_14_1 $p_14_1 Kwota podatku od sumy wartości sprzedaży netto objętej stawką podstawową - aktualnie 23% albo 22%. W przypadku faktur zaliczkowych, kwota podatku wyliczona według wzoru, o którym mowa w art. 106f ust. 1 pkt 3 ustawy. W przypadku faktur korygujących, kwota różnicy, o której mowa w art. 106j ust. 2 pkt 5 ustawy
     * @param Optional|P_14_1W $p_14_1w W przypadku gdy faktura jest wystawiona w walucie obcej, kwota podatku od sumy wartości sprzedaży netto objętej stawką podstawową, przeliczona zgodnie z przepisami Działu VI w związku z art. 106e ust. 11 ustawy - aktualnie 23% albo 22%. W przypadku faktur zaliczkowych, kwota podatku wyliczona według wzoru, o którym mowa w art. 106f ust. 1 pkt 3 ustawy. W przypadku faktur korygujących, kwota różnicy, o której mowa w art. 106j ust. 2 pkt 5 ustawy
     * @return void
     */
    public function __construct(
        public P_13_1 $p_13_1,
        public P_14_1 $p_14_1,
        public Optional | P_14_1W $p_14_1w = new Optional(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_13_1group = $dom->createElement('P_13_1Group');
        $dom->appendChild($p_13_1group);

        $p_13_1 = $dom->createElement('P_13_1');
        $p_13_1->appendChild($dom->createTextNode((string) $this->p_13_1));

        $p_13_1group->appendChild($p_13_1);

        $p_14_1 = $dom->createElement('P_14_1');
        $p_14_1->appendChild($dom->createTextNode((string) $this->p_14_1));

        $p_13_1group->appendChild($p_14_1);

        if ($this->p_14_1w instanceof P_14_1W) {
            $p_14_1w = $dom->createElement('P_14_1W');
            $p_14_1w->appendChild($dom->createTextNode((string) $this->p_14_1w));

            $p_13_1group->appendChild($p_14_1w);
        }

        return $dom;
    }
}
