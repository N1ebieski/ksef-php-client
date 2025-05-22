<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_PMarzy_2;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class P_PMarzy_2Group extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param P_PMarzy_2 $p_PMarzy_2 Znacznik świadczenia usług turystyki, dla których podstawę opodatkowania stanowi marża, zgodnie z art. 119 ust. 1 ustawy, a faktura dokumentująca świadczenie zawiera wyrazy "procedura marży dla biur podróży"
     * @return void
     */
    public function __construct(
        public P_PMarzy_2 $p_PMarzy_2 = P_PMarzy_2::Default,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_PMarzy_2Group = $dom->createElement('P_PMarzy_2Group');
        $dom->appendChild($p_PMarzy_2Group);

        $p_PMarzy_2 = $dom->createElement('P_PMarzy_2');
        $p_PMarzy_2->appendChild($dom->createTextNode((string) $this->p_PMarzy_2->value));

        $p_PMarzy_2Group->appendChild($p_PMarzy_2);

        return $dom;
    }
}
