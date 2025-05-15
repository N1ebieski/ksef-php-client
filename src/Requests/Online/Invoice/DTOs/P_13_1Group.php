<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_13_1;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_14_1;
use N1ebieski\KSEFClient\Requests\Online\ValueObjects\XmlNamespace;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class P_13_1Group extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param P_13_1 $p_13_1 Suma wartości sprzedaży netto ze stawką podstawową - aktualnie 23% albo 22%. W przypadku faktur zaliczkowych, kwota zaliczki netto. W przypadku faktur korygujących, kwota różnicy, o której mowa w art. 106j ust. 2 pkt 5 ustawy
     * @param P_14_1 $p_14_1 Kwota podatku od sumy wartości sprzedaży netto objętej stawką podstawową - aktualnie 23% albo 22%. W przypadku faktur zaliczkowych, kwota podatku wyliczona według wzoru, o którym mowa w art. 106f ust. 1 pkt 3 ustawy. W przypadku faktur korygujących, kwota różnicy, o której mowa w art. 106j ust. 2 pkt 5 ustawy
     * @return void
     */
    public function __construct(
        public P_13_1 $p_13_1,
        public P_14_1 $p_14_1,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_13_1group = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'P_13_1Group');
        $dom->appendChild($p_13_1group);

        $p_13_1 = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'P_13_1');
        $p_13_1->appendChild($dom->createTextNode((string) $this->p_13_1));

        $p_13_1group->appendChild($p_13_1);

        $p_14_1 = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'P_14_1');
        $p_14_1->appendChild($dom->createTextNode((string) $this->p_14_1));

        $p_13_1group->appendChild($p_14_1);

        return $dom;
    }
}
