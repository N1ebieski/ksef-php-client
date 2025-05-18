<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_19B;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class P_19BGroup extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param P_19B $p_19b Jeśli pole P_19 równa się "1" - należy wskazać przepis dyrektywy 2006/112/WE, który zwalnia od podatku taką dostawę towarów lub takie świadczenie usług
     * @return void
     */
    public function __construct(
        public P_19B $p_19b,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_19bgroup = $dom->createElement('P_19BGroup');
        $dom->appendChild($p_19bgroup);

        $p_19b = $dom->createElement('P_19B');
        $p_19b->appendChild($dom->createTextNode((string) $this->p_19b));

        $p_19bgroup->appendChild($p_19b);

        return $dom;
    }
}
