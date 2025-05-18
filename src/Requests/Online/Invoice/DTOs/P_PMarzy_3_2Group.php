<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_PMarzy_3_2;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class P_PMarzy_3_2Group extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param P_PMarzy_3_2 $p_pmarzy_3_2 Znacznik dostawy dzieł sztuki dla których podstawę opodatkowania stanowi marża, zgodnie z art. 120 ustawy, a faktura dokumentująca dostawę zawiera wyrazy "procedura marży - dzieła sztuki"
     * @return void
     */
    public function __construct(
        public P_PMarzy_3_2 $p_pmarzy_3_2 = P_PMarzy_3_2::Default,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_pmarzy_3_2group = $dom->createElement('P_PMarzy_3_2Group');
        $dom->appendChild($p_pmarzy_3_2group);

        $p_pmarzy_3_2 = $dom->createElement('P_PMarzy_3_2');
        $p_pmarzy_3_2->appendChild($dom->createTextNode((string) $this->p_pmarzy_3_2->value));

        $p_pmarzy_3_2group->appendChild($p_pmarzy_3_2);

        return $dom;
    }
}
