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
use N1ebieski\KSEFClient\Support\DTO;
use N1ebieski\KSEFClient\ValueObjects\NIP;

final readonly class P_22NGroup extends DTO implements DomSerializableInterface
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
        $p_22n->appendChild($dom->createTextNode((string) $this->p_22n));

        $p_22ngroup->appendChild($p_22n);

        $dom->appendChild($p_22ngroup);

        return $dom;
    }
}
