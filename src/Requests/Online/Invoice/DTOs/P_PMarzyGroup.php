<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use DOMElement;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_PMarzy;
use N1ebieski\KSEFClient\Requests\Online\ValueObjects\XmlNamespace;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class P_PMarzyGroup extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param P_PMarzy $p_pmarzy Znacznik wystąpienia procedur marży, o których mowa w art. 119 lub art. 120 ustawy
     * @return void
     */
    public function __construct(
        public P_PMarzy_2Group | P_PMarzy_3_1Group | P_PMarzy_3_2Group | P_PMarzy_3_3Group $p_pmarzy_2_3group,
        public P_PMarzy $p_pmarzy = P_PMarzy::Default
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_pmarzygroup = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'P_PMarzyGroup');
        $dom->appendChild($p_pmarzygroup);

        $p_pmarzy = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'P_PMarzy');
        $p_pmarzy->appendChild($dom->createTextNode((string) $this->p_pmarzy->value));

        $p_pmarzygroup->appendChild($p_pmarzy);

        /** @var DOMElement $p_pmarzy2_3group */
        $p_pmarzy2_3group = $this->p_pmarzy_2_3group->toDom()->documentElement;

        foreach ($p_pmarzy2_3group->childNodes as $child) {
            $p_pmarzygroup->appendChild($dom->importNode($child, true));
        }

        return $dom;
    }
}
