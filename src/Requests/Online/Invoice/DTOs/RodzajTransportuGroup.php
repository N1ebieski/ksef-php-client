<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\RodzajTransportu;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class RodzajTransportuGroup extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param RodzajTransportu $rodzajTransportu Rodzaj zastosowanego transportu w przypadku dokonanej dostawy towarów
     */
    public function __construct(
        public RodzajTransportu $rodzajTransportu
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $rodzajTransportuGroup = $dom->createElement('RodzajTransportuGroup');
        $dom->appendChild($rodzajTransportuGroup);

        $rodzajTransportu = $dom->createElement('RodzajTransportu');
        $rodzajTransportu->appendChild($dom->createTextNode((string) $this->rodzajTransportu->value));

        $rodzajTransportuGroup->appendChild($rodzajTransportu);

        return $dom;
    }
}
