<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_22;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_42_5;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class P_22Group extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param P_22 $p_22 Znacznik wewnątrzwspólnotowej dostawy nowych środków transportu
     * @param P_42_5 $p_42_5 Jeśli występuje obowiązek, o którym mowa w art. 42 ust. 5 ustawy, należy podać wartość "1", w przeciwnym przypadku - wartość "2
     * @return void
     */
    public function __construct(
        public P_42_5 $p_42_5,
        public P_22 $p_22 = P_22::Default,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_22group = $dom->createElement('P_22Group');
        $dom->appendChild($p_22group);

        $p_42_5 = $dom->createElement('P_42_5');
        $p_42_5->appendChild($dom->createTextNode((string) $this->p_42_5->value));

        $p_22group->appendChild($p_42_5);

        $p_22 = $dom->createElement('P_22');
        $p_22->appendChild($dom->createTextNode((string) $this->p_22->value));

        $p_22group->appendChild($p_22);

        return $dom;
    }
}
