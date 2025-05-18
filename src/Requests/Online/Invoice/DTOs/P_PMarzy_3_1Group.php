<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_PMarzy_3_1;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class P_PMarzy_3_1Group extends AbstractDTO implements DomSerializableInterface
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
        $p_pmarzy_3_1->appendChild($dom->createTextNode((string) $this->p_pmarzy_3_1->value));

        $p_pmarzy_3_1group->appendChild($p_pmarzy_3_1);

        return $dom;
    }
}
