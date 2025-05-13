<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_PMarzyN;
use N1ebieski\KSEFClient\Resources\Online\ValueObjects\XmlNamespace;
use N1ebieski\KSEFClient\Support\DTO;

final readonly class PMarzy extends DTO implements DomSerializableInterface
{
    public function __construct(
        public P_PMarzyGroup | P_PMarzyNGroup $p_pmarzygroup = new P_PMarzyNGroup(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $pmarzy = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'PMarzy');
        $dom->appendChild($pmarzy);

        $p_pmarzygroup = $this->p_pmarzygroup->toDom()->documentElement;

        foreach ($p_pmarzygroup->childNodes as $child) {
            $pmarzy->appendChild($dom->importNode($child, true));
        }

        $dom->appendChild($pmarzy);

        return $dom;
    }
}
