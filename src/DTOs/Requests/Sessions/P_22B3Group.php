<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_22B3;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final class P_22B3Group extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    /**
     * @param P_22B3 $p_22B3 Jeśli dostawa dotyczy pojazdów lądowych, o których mowa w art. 2 pkt 10 lit. a ustawy - można podać numer podwozia
     */
    public function __construct(
        public readonly P_22B3 $p_22B3,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_22B3Group = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_22B3Group');
        $dom->appendChild($p_22B3Group);

        $p_22B3 = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_22B3');
        $p_22B3->appendChild($dom->createTextNode($this->p_22B3->value));

        $p_22B3Group->appendChild($p_22B3);

        return $dom;
    }

    public static function fromXmlArray(array $data): self
    {
        return new self(p_22B3: new P_22B3($data['p_22B3']));
    }
}
