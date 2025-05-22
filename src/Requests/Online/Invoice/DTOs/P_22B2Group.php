<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_22B2;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class P_22B2Group extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param P_22B2 $p_22b2 Jeśli dostawa dotyczy pojazdów lądowych, o których mowa w art. 2 pkt 10 lit. a ustawy - można podać numer nadwozia
     */
    public function __construct(
        public P_22B2 $p_22b2,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_22b2group = $dom->createElement('P_22B2Group');
        $dom->appendChild($p_22b2group);

        $p_22b2 = $dom->createElement('P_22B2');
        $p_22b2->appendChild($dom->createTextNode($this->p_22b2->value));

        $p_22b2group->appendChild($p_22b2);

        return $dom;
    }
}
