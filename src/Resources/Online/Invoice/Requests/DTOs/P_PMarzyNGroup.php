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
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_PMarzyN;
use N1ebieski\KSEFClient\Support\DTO;
use N1ebieski\KSEFClient\ValueObjects\NIP;

final readonly class P_PMarzyNGroup extends DTO implements DomSerializableInterface
{
    /**
     * @param P_PMarzyN $p_pmarzyn Znacznik braku wystąpienia procedur marży, o których mowa w art. 119 lub art. 120 ustawy
     * @return void
     */
    public function __construct(
        public P_PMarzyN $p_pmarzyn = P_PMarzyN::Default,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_pmarzyngroup = $dom->createElement('P_PMarzyNGroup');
        $dom->appendChild($p_pmarzyngroup);

        $p_pmarzyn = $dom->createElement('P_PMarzyN');
        $p_pmarzyn->appendChild($dom->createTextNode((string) $this->p_pmarzyn->value));

        $p_pmarzyngroup->appendChild($p_pmarzyn);

        return $dom;
    }
}
