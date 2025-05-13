<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_6;
use N1ebieski\KSEFClient\Resources\Online\ValueObjects\XmlNamespace;
use N1ebieski\KSEFClient\Support\DTO;

final readonly class P_6Group extends DTO implements DomSerializableInterface
{
    /**
     * @param P_6 $p_6 Data dokonania lub zakończenia dostawy towarów lub wykonania usługi lub data otrzymania zapłaty, o której mowa w art. 106b ust. 1 pkt 4 ustawy, o ile taka data jest określona i różni się od daty wystawienia faktury. Pole wypełnia się w przypadku, gdy dla wszystkich pozycji faktury data jest wspólna
     * @return void
     */
    public function __construct(
        public P_6 $p_6,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p6Group = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'P_6Group');
        $dom->appendChild($p6Group);

        $p6 = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'P_6');
        $p6->appendChild($dom->createTextNode((string) $this->p_6));

        $p6Group->appendChild($p6);

        return $dom;
    }
}
