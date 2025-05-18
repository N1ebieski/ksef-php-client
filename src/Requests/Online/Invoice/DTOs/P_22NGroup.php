<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_22N;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class P_22NGroup extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param P_22N $p_22n Znacznik braku wewnątrzwspólnotowej dostawy nowych środków transportu
     * @return void
     */
    public function __construct(
        public P_22N $p_22n = P_22N::Default,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_22ngroup = $dom->createElement('P_22NGroup');
        $dom->appendChild($p_22ngroup);

        $p_22n = $dom->createElement('P_22N');
        $p_22n->appendChild($dom->createTextNode((string) $this->p_22n->value));

        $p_22ngroup->appendChild($p_22n);

        return $dom;
    }
}
