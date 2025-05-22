<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_PMarzyN;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class P_PMarzyNGroup extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param P_PMarzyN $p_PMarzyN Znacznik braku wystąpienia procedur marży, o których mowa w art. 119 lub art. 120 ustawy
     * @return void
     */
    public function __construct(
        public P_PMarzyN $p_PMarzyN = P_PMarzyN::Default,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_PMarzyNGroup = $dom->createElement('P_PMarzyNGroup');
        $dom->appendChild($p_PMarzyNGroup);

        $p_PMarzyN = $dom->createElement('P_PMarzyN');
        $p_PMarzyN->appendChild($dom->createTextNode((string) $this->p_PMarzyN->value));

        $p_PMarzyNGroup->appendChild($p_PMarzyN);

        return $dom;
    }
}
