<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_13_3;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_14_3;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_14_3W;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class P_13_3Group extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    /**
     * @param P_13_3 $p_13_3 Suma wartości sprzedaży netto objętej stawką obniżoną drugą - aktualnie 5%. W przypadku faktur zaliczkowych, kwota zaliczki netto. W przypadku faktur korygujących, kwota różnicy, o której mowa w art. 106j ust. 2 pkt 5 ustawy
     * @param P_14_3 $p_14_3 Kwota podatku od sumy wartości sprzedaży netto objętej stawką obniżoną drugą - aktualnie 5%. W przypadku faktur zaliczkowych, kwota podatku wyliczona według wzoru, o którym mowa w art. 106f ust. 1 pkt 3 ustawy. W przypadku faktur korygujących, kwota różnicy, o której mowa w art. 106j ust. 2 pkt 5 ustawy
     * @param Optional|P_14_3W $p_14_3W W przypadku gdy faktura jest wystawiona w walucie obcej, kwota podatku od sumy wartości sprzedaży netto objętej stawką obniżoną drugą, przeliczona zgodnie z przepisami Działu VI w związku z art. 106e ust. 11 ustawy - aktualnie 5%. W przypadku faktur zaliczkowych, kwota podatku wyliczona według wzoru, o którym mowa w art. 106f ust. 1 pkt 3 ustawy. W przypadku faktur korygujących, kwota różnicy, o której mowa w art. 106j ust. 2 pkt 5 ustawy
     */
    public function __construct(
        public readonly P_13_3 $p_13_3,
        public readonly P_14_3 $p_14_3,
        public readonly Optional | P_14_3W $p_14_3W = new Optional(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_13_3Group = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_13_3Group');
        $dom->appendChild($p_13_3Group);

        $p_13_3 = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_13_3');
        $p_13_3->appendChild($dom->createTextNode((string) $this->p_13_3));

        $p_13_3Group->appendChild($p_13_3);

        $p_14_3 = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_14_3');
        $p_14_3->appendChild($dom->createTextNode((string) $this->p_14_3));

        $p_13_3Group->appendChild($p_14_3);

        if ($this->p_14_3W instanceof P_14_3W) {
            $p_14_3W = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_14_3W');
            $p_14_3W->appendChild($dom->createTextNode((string) $this->p_14_3W));

            $p_13_3Group->appendChild($p_14_3W);
        }

        return $dom;
    }

    public static function fromXmlArray(array $data): self
    {
        return new self(
            p_13_3: new P_13_3($data['P_13_3']),
            p_14_3: new P_14_3($data['P_14_3']),
            p_14_3W: isset($data['P_14_3W']) ? new P_14_3W($data['P_14_3W']) : new Optional(),
        );
    }
}
