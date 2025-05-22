<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_13_4;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_14_4;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_14_4W;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;

final readonly class P_13_4Group extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param P_13_4 $p_13_4 Suma wartości sprzedaży netto objętej ryczałtem dla taksówek osobowych. W przypadku faktur zaliczkowych, kwota zaliczki netto. W przypadku faktur korygujących, kwota różnicy, o której mowa w art. 106j ust. 2 pkt 5 ustawy
     * @param P_14_4 $p_14_4 Kwota podatku od sumy wartości sprzedaży netto w przypadku ryczałtu dla taksówek osobowych. W przypadku faktur zaliczkowych, kwota podatku wyliczona według wzoru, o którym mowa w art. 106f ust. 1 pkt 3 ustawy. W przypadku faktur korygujących, kwota różnicy, o której mowa w art. 106j ust. 2 pkt 5 ustawy
     * @param Optional|P_14_4W $p_14_4W W przypadku gdy faktura jest wystawiona w walucie obcej, kwota podatku ryczałtu dla taksówek osobowych, przeliczona zgodnie z przepisami Działu VI w związku z art. 106e ust. 11 ustawy. W przypadku faktur zaliczkowych, kwota podatku wyliczona według wzoru, o którym mowa w art. 106f ust. 1 pkt 3 ustawy. W przypadku faktur korygujących, kwota różnicy, o której mowa w art. 106j ust. 2 pkt 5 ustawy
     * @return void
     */
    public function __construct(
        public P_13_4 $p_13_4,
        public P_14_4 $p_14_4,
        public Optional | P_14_4W $p_14_4W = new Optional(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_13_4Group = $dom->createElement('P_13_4Group');
        $dom->appendChild($p_13_4Group);

        $p_13_4 = $dom->createElement('P_13_4');
        $p_13_4->appendChild($dom->createTextNode((string) $this->p_13_4));

        $p_13_4Group->appendChild($p_13_4);

        $p_14_4 = $dom->createElement('P_14_4');
        $p_14_4->appendChild($dom->createTextNode((string) $this->p_14_4));

        $p_13_4Group->appendChild($p_14_4);

        if ($this->p_14_4W instanceof P_14_4W) {
            $p_14_4W = $dom->createElement('P_14_4W');
            $p_14_4W->appendChild($dom->createTextNode((string) $this->p_14_4W));

            $p_13_4Group->appendChild($p_14_4W);
        }

        return $dom;
    }
}
