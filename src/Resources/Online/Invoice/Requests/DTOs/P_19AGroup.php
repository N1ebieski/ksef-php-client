<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_19A;
use N1ebieski\KSEFClient\Resources\Online\ValueObjects\XmlNamespace;
use N1ebieski\KSEFClient\Support\DTO;

final readonly class P_19AGroup extends DTO implements DomSerializableInterface
{
    /**
     * @param P_19A $p_19a Jeśli pole P_19 równa się "1" - należy wskazać przepis ustawy albo aktu wydanego na podstawie ustawy, na podstawie którego podatnik stosuje zwolnienie od podatku
     * @return void
     */
    public function __construct(
        public P_19A $p_19a,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_19agroup = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'P_19AGroup');
        $dom->appendChild($p_19agroup);

        $p_19a = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'P_19A');
        $p_19a->appendChild($dom->createTextNode((string) $this->p_19a));

        $p_19agroup->appendChild($p_19a);

        return $dom;
    }
}
