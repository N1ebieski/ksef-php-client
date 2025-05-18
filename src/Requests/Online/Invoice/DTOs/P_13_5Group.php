<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_13_5;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_14_5;
use N1ebieski\KSEFClient\Requests\Online\ValueObjects\XmlNamespace;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class P_13_5Group extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param P_13_5 $p_13_5 Suma wartości sprzedaży netto w przypadku procedury szczególnej, o której mowa w dziale XII w rozdziale 6a ustawy. W przypadku faktur zaliczkowych, kwota zaliczki netto. W przypadku faktur korygujących, kwota różnicy, o której mowa w art. 106j ust. 2 pkt 5 ustawy
     * @param P_14_5|null $p_14_5 Kwota podatku od wartości dodanej w przypadku procedury szczególnej, o której mowa w dziale XII w rozdziale 6a ustawy. W przypadku faktur zaliczkowych, kwota podatku wyliczona według wzoru, o którym mowa w art. 106f ust. 1 pkt 3 ustawy. W przypadku faktur korygujących, kwota różnicy, o której mowa w art. 106j ust. 2 pkt 5 ustawy
     * @return void
     */
    public function __construct(
        public P_13_5 $p_13_5,
        public ?P_14_5 $p_14_5 = null,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_13_5group = $dom->createElement('P_13_5Group');
        $dom->appendChild($p_13_5group);

        $p_13_5 = $dom->createElement('P_13_5');
        $p_13_5->appendChild($dom->createTextNode((string) $this->p_13_5));

        $p_13_5group->appendChild($p_13_5);

        if ($this->p_14_5 instanceof P_14_5) {
            $p_14_5 = $dom->createElement('P_14_5');
            $p_14_5->appendChild($dom->createTextNode((string) $this->p_14_5));

            $p_13_5group->appendChild($p_14_5);
        }

        return $dom;
    }
}
