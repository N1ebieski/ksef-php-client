<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_13_5;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_14_5;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;

final class P_13_5Group extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    /**
     * @param P_13_5 $p_13_5 Suma wartości sprzedaży netto w przypadku procedury szczególnej, o której mowa w dziale XII w rozdziale 6a ustawy. W przypadku faktur zaliczkowych, kwota zaliczki netto. W przypadku faktur korygujących, kwota różnicy, o której mowa w art. 106j ust. 2 pkt 5 ustawy
     * @param P_14_5|Optional $p_14_5 Kwota podatku od wartości dodanej w przypadku procedury szczególnej, o której mowa w dziale XII w rozdziale 6a ustawy. W przypadku faktur zaliczkowych, kwota podatku wyliczona według wzoru, o którym mowa w art. 106f ust. 1 pkt 3 ustawy. W przypadku faktur korygujących, kwota różnicy, o której mowa w art. 106j ust. 2 pkt 5 ustawy
     */
    public function __construct(
        public readonly P_13_5 $p_13_5,
        public readonly Optional | P_14_5 $p_14_5 = new Optional(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_13_5Group = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_13_5Group');
        $dom->appendChild($p_13_5Group);

        $p_13_5 = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_13_5');
        $p_13_5->appendChild($dom->createTextNode((string) $this->p_13_5));

        $p_13_5Group->appendChild($p_13_5);

        if ($this->p_14_5 instanceof P_14_5) {
            $p_14_5 = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_14_5');
            $p_14_5->appendChild($dom->createTextNode((string) $this->p_14_5));

            $p_13_5Group->appendChild($p_14_5);
        }

        return $dom;
    }

    public static function fromXmlArray(array $data): self
    {
        return new self(
            p_13_5: new P_13_5($data['P_13_5']),
            p_14_5: isset($data['P_14_5']) ? new P_14_5($data['P_14_5']) : new Optional(),
        );
    }
}
