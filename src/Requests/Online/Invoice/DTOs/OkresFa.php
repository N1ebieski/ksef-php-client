<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_6_Do;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_6_Od;
use N1ebieski\KSEFClient\Requests\Online\ValueObjects\XmlNamespace;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class OkresFa extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param P_6_Od $p_6_od Data początkowa okresu, którego dotyczy faktura
     * @param P_6_Do $p_6_do Data końcowa okresu, którego dotyczy faktura - data dokonania lub zakończenia dostawy towarów lub wykonania usługi
     * @return void
     */
    public function __construct(
        public P_6_Od $p_6_od,
        public P_6_Do $p_6_do
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $okresFa = $dom->createElement('OkresFa');
        $dom->appendChild($okresFa);

        $p6Od = $dom->createElement('P_6_Od');
        $p6Od->appendChild($dom->createTextNode((string) $this->p_6_od));

        $okresFa->appendChild($p6Od);

        $p6Do = $dom->createElement('P_6_Do');
        $p6Do->appendChild($dom->createTextNode((string) $this->p_6_do));

        $okresFa->appendChild($p6Do);

        $dom->appendChild($okresFa);

        return $dom;
    }
}
