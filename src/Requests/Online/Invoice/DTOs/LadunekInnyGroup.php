<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\LadunekInny;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\OpisInnegoLadunku;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class LadunekInnyGroup extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param OpisInnegoLadunku $opisInnegoLadunku Opis innego ładunku, w tym ładunek mieszany
     * @param LadunekInny $ladunekInny Znacznik innego ładunku: 1 - inny ładunek
     */
    public function __construct(
        public OpisInnegoLadunku $opisInnegoLadunku,
        public LadunekInny $ladunekInny = LadunekInny::Default
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $ladunekInnyGroup = $dom->createElement('LadunekInnyGroup');
        $dom->appendChild($ladunekInnyGroup);

        $ladunekInny = $dom->createElement('LadunekInny');
        $ladunekInny->appendChild($dom->createTextNode((string) $this->ladunekInny->value));

        $ladunekInnyGroup->appendChild($ladunekInny);

        $opisInnegoLadunku = $dom->createElement('OpisInnegoLadunku');
        $opisInnegoLadunku->appendChild($dom->createTextNode((string) $this->opisInnegoLadunku));

        $ladunekInnyGroup->appendChild($opisInnegoLadunku);

        return $dom;
    }
}
