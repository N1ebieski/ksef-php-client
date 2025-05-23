<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\OpisLadunku;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class OpisLadunkuGroup extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param OpisLadunku $opisLadunku Rodzaj ładunku
     */
    public function __construct(
        public OpisLadunku $opisLadunku
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $opisLadunkuGroup = $dom->createElement('OpisLadunkuGroup');
        $dom->appendChild($opisLadunkuGroup);

        $opisLadunku = $dom->createElement('OpisLadunku');
        $opisLadunku->appendChild($dom->createTextNode((string) $this->opisLadunku->value));

        $opisLadunkuGroup->appendChild($opisLadunku);

        return $dom;
    }
}
