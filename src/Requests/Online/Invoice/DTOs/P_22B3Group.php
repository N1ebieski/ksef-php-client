<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_22B3;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class P_22B3Group extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param P_22B3 $p_22B3 Jeśli dostawa dotyczy pojazdów lądowych, o których mowa w art. 2 pkt 10 lit. a ustawy - można podać numer podwozia
     */
    public function __construct(
        public P_22B3 $p_22B3,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_22B3Group = $dom->createElement('P_22B3Group');
        $dom->appendChild($p_22B3Group);

        $p_22B3 = $dom->createElement('P_22B3');
        $p_22B3->appendChild($dom->createTextNode($this->p_22B3->value));

        $p_22B3Group->appendChild($p_22B3);

        return $dom;
    }
}
