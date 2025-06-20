<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_19A;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class P_19AGroup extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param P_19A $p_19A Jeśli pole P_19 równa się "1" - należy wskazać przepis ustawy albo aktu wydanego na podstawie ustawy, na podstawie którego podatnik stosuje zwolnienie od podatku
     * @return void
     */
    public function __construct(
        public P_19A $p_19A,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_19AGroup = $dom->createElement('P_19AGroup');
        $dom->appendChild($p_19AGroup);

        $p_19A = $dom->createElement('P_19A');
        $p_19A->appendChild($dom->createTextNode((string) $this->p_19A));

        $p_19AGroup->appendChild($p_19A);

        return $dom;
    }
}
