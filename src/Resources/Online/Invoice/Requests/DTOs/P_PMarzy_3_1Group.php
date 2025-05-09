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
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_PMarzy_2;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_PMarzy_3_1;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_PMarzyN;
use N1ebieski\KSEFClient\Support\DTO;
use N1ebieski\KSEFClient\ValueObjects\NIP;

final readonly class P_PMarzy_3_1Group extends DTO implements DomSerializableInterface
{
    /**
     * @param P_PMarzy_3_1 $p_pmarzy_3_1 Znacznik dostawy towarów używanych dla których podstawę opodatkowania stanowi marża, zgodnie z art. 120 ustawy, a faktura dokumentująca dostawę zawiera wyrazy "procedura marży - towary używane"
     * @return void
     */
    public function __construct(
        public P_PMarzy_3_1 $p_pmarzy_3_1 = P_PMarzy_3_1::Default,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_pmarzy_3_1group = $dom->createElement('P_PMarzy_3_1Group');
        $dom->appendChild($p_pmarzy_3_1group);

        $p_pmarzy_3_1 = $dom->createElement('P_PMarzy_3_1');
        $p_pmarzy_3_1->appendChild($dom->createTextNode((string) $this->p_pmarzy_3_1));

        $p_pmarzy_3_1group->appendChild($p_pmarzy_3_1);

        $dom->appendChild($p_pmarzy_3_1group);

        return $dom;
    }
}
