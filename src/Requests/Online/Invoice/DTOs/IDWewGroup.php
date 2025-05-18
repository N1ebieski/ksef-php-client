<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\IDWew;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class IDWewGroup extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param IDWew $iDWew Identyfikator wewnętrzny z NIP
     * @return void
     */
    public function __construct(
        public IDWew $iDWew,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $iDWewgroup = $dom->createElement('IDWewGroup');
        $dom->appendChild($iDWewgroup);

        $iDWew = $dom->createElement('IDWew');
        $iDWew->appendChild($dom->createTextNode((string) $this->iDWew->value));

        $iDWewgroup->appendChild($iDWew);

        return $dom;
    }
}
