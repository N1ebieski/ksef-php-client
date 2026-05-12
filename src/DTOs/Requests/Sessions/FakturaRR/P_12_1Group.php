<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions\FakturaRR;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\XmlNormalizableInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\FakturaRR\P_12_1;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\FakturaRR\P_12_1W;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\FakturaRR\P_12_2;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class P_12_1Group extends AbstractDTO implements DomSerializableInterface, XmlNormalizableInterface
{
    /**
     * @param P_12_1 $p_12_1 Kwota należności ogółem wraz z kwotą zryczałtowanego zwrotu podatku, wyrażona cyfrowo. W przypadku faktur korygujących - kwota różnicy kwoty należności ogółem z kwotą zryczałtowanego zwrotu podatku.
     * @param P_12_2 $p_12_2 Kwota należności ogółem wraz z kwotą zryczałtowanego zwrotu podatku, wyrażona słownie. W przypadku faktur korygujących - kwota różnicy kwoty należności ogółem z kwotą zryczałtowanego zwrotu podatku.
     * @param Optional|P_12_1W $p_12_1W Kwota należności ogółem wraz z kwotą zryczałtowanego zwrotu podatku, wyrażona cyfrowo w PLN, przeliczona zgodnie z art. 116 ust. 2b ustawy. W przypadku faktur korygujących - kwota różnicy kwoty należności ogółem z kwotą zryczałtowanego zwrotu podatku przeliczona zgodnie z art. 116 ust. 2b ustawy
     */
    public function __construct(
        public readonly P_12_1 $p_12_1,
        public readonly P_12_2 $p_12_2,
        public readonly Optional | P_12_1W $p_12_1W = new Optional(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_12_1Group = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'P_12_1Group');
        $dom->appendChild($p_12_1Group);

        $p_12_1 = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'P_12_1');
        $p_12_1->appendChild($dom->createTextNode((string) $this->p_12_1));

        $p_12_1Group->appendChild($p_12_1);

        if ($this->p_12_1W instanceof P_12_1W) {
            $p_12_1W = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'P_12_1W');
            $p_12_1W->appendChild($dom->createTextNode((string) $this->p_12_1W));

            $p_12_1Group->appendChild($p_12_1W);
        }

        $p_12_2 = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'P_12_2');
        $p_12_2->appendChild($dom->createTextNode((string) $this->p_12_2));

        $p_12_1Group->appendChild($p_12_2);

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        return Arr::only($data, ['P_12_1', 'P_12_2', 'P_12_1W']);
    }
}
