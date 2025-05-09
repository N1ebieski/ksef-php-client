<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\KodKraju;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\KodUE;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\Nazwa;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\NrID;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\NrVatUE;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_19N;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_22N;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_6;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_PMarzy;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_PMarzyN;
use N1ebieski\KSEFClient\Support\DTO;
use N1ebieski\KSEFClient\ValueObjects\NIP;

final readonly class P_PMarzyGroup extends DTO implements DomSerializableInterface
{
    /**
     * @param P_PMarzy $p_pmarzy Znacznik wystąpienia procedur marży, o których mowa w art. 119 lub art. 120 ustawy
     * @return void
     */
    public function __construct(
        public P_PMarzy $p_pmarzy = P_PMarzy::Default,
        public P_PMarzy_2Group | P_PMarzy_3_1Group | P_PMarzy_3_2Group | P_PMarzy_3_3Group $p_pmarzy_2_3group
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_pmarzygroup = $dom->createElement('P_PMarzyGroup');
        $dom->appendChild($p_pmarzygroup);

        $p_pmarzy = $dom->createElement('P_PMarzy');
        $p_pmarzy->appendChild($dom->createTextNode((string) $this->p_pmarzy));

        $p_pmarzygroup->appendChild($p_pmarzy);

        $p_pmarzy2_3group = $this->p_pmarzy_2_3group->toDom()->documentElement;

        foreach ($p_pmarzy2_3group->childNodes as $child) {
            $p_pmarzygroup->appendChild($dom->importNode($child, true));
        }

        $dom->appendChild($p_pmarzygroup);

        return $dom;
    }
}
