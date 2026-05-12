<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions\FakturaRR;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\XmlNormalizableInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\FakturaRR\P_11_1;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\FakturaRR\P_11_1W;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\FakturaRR\P_11_2;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\FakturaRR\P_11_2W;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class P_11_1Group extends AbstractDTO implements DomSerializableInterface, XmlNormalizableInterface
{
    /**
     * @param P_11_1 $p_11_1 Wartość nabytych produktów rolnych lub usług rolniczych bez kwoty zryczałtowanego zwrotu podatku. W przypadku faktur korygujących - kwota różnicy, o której mowa w art. 116 ust. 5e pkt 5 ustawy.
     * @param P_11_2 $p_11_2 Kwota zryczałtowanego zwrotu podatku. W przypadku faktur korygujących - kwota różnicy, o której mowa w art. 116 ust. 5e pkt 5 ustawy.
     * @param Optional|P_11_1W $p_11_1W Wartość nabytych produktów rolnych lub usług rolniczych bez kwoty zryczałtowanego zwrotu podatku, przeliczona zgodnie z art. 116 ust. 2b i 2c ustawy, w przypadku gdy faktura jest wystawiona w walucie obcej. W przypadku faktur korygujących, podaje się kwotę różnicy, o której mowa w art. 116 ust. 5e pkt 5 ustawy
     * @param Optional|P_11_2W $p_11_2W Kwota zryczałtowanego zwrotu podatku, przeliczona zgodnie z art. 116 ust. 2b i 2c ustawy, w przypadku gdy faktura jest wystawiona w walucie obcej. W przypadku faktur korygujących, kwota różnicy, o której mowa w art. 116 ust. 5e pkt 5 ustawy
     */
    public function __construct(
        public readonly P_11_1 $p_11_1,
        public readonly P_11_2 $p_11_2,
        public readonly Optional | P_11_1W $p_11_1W = new Optional(),
        public readonly Optional | P_11_2W $p_11_2W = new Optional(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_11_1Group = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'P_11_1Group');
        $dom->appendChild($p_11_1Group);

        $p_11_1 = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'P_11_1');
        $p_11_1->appendChild($dom->createTextNode((string) $this->p_11_1));

        $p_11_1Group->appendChild($p_11_1);

        if ($this->p_11_1W instanceof P_11_1W) {
            $p_11_1W = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'P_11_1W');
            $p_11_1W->appendChild($dom->createTextNode((string) $this->p_11_1W));

            $p_11_1Group->appendChild($p_11_1W);
        }

        $p_11_2 = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'P_11_2');
        $p_11_2->appendChild($dom->createTextNode((string) $this->p_11_2));

        $p_11_1Group->appendChild($p_11_2);

        if ($this->p_11_2W instanceof P_11_2W) {
            $p_11_2W = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'P_11_2W');
            $p_11_2W->appendChild($dom->createTextNode((string) $this->p_11_2W));

            $p_11_1Group->appendChild($p_11_2W);
        }

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        return Arr::only($data, ['P_11_1', 'P_11_2', 'P_11_1W', 'P_11_2W']);
    }
}
