<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use DOMElement;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_PMarzy;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class P_PMarzyGroup extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param P_PMarzy $p_PMarzy Znacznik wystąpienia procedur marży, o których mowa w art. 119 lub art. 120 ustawy
     * @return void
     */
    public function __construct(
        public P_PMarzy_2Group | P_PMarzy_3_1Group | P_PMarzy_3_2Group | P_PMarzy_3_3Group $p_PMarzy_2_3Group,
        public P_PMarzy $p_PMarzy = P_PMarzy::Default
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_PMarzyGroup = $dom->createElement('P_PMarzyGroup');
        $dom->appendChild($p_PMarzyGroup);

        $p_PMarzy = $dom->createElement('P_PMarzy');
        $p_PMarzy->appendChild($dom->createTextNode((string) $this->p_PMarzy->value));

        $p_PMarzyGroup->appendChild($p_PMarzy);

        /** @var DOMElement $p_PMarzy2_3Group */
        $p_PMarzy2_3Group = $this->p_PMarzy_2_3Group->toDom()->documentElement;

        foreach ($p_PMarzy2_3Group->childNodes as $child) {
            $p_PMarzyGroup->appendChild($dom->importNode($child, true));
        }

        return $dom;
    }
}
