<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\LadunekInny;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\OpisInnegoLadunku;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final class LadunekInnyGroup extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    /**
     * @param OpisInnegoLadunku $opisInnegoLadunku Opis innego ładunku, w tym ładunek mieszany
     * @param LadunekInny $ladunekInny Znacznik innego ładunku: 1 - inny ładunek
     */
    public function __construct(
        public readonly OpisInnegoLadunku $opisInnegoLadunku,
        public readonly LadunekInny $ladunekInny = LadunekInny::Default
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $ladunekInnyGroup = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'LadunekInnyGroup');
        $dom->appendChild($ladunekInnyGroup);

        $ladunekInny = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'LadunekInny');
        $ladunekInny->appendChild($dom->createTextNode((string) $this->ladunekInny->value));

        $ladunekInnyGroup->appendChild($ladunekInny);

        $opisInnegoLadunku = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'OpisInnegoLadunku');
        $opisInnegoLadunku->appendChild($dom->createTextNode((string) $this->opisInnegoLadunku));

        $ladunekInnyGroup->appendChild($opisInnegoLadunku);

        return $dom;
    }

    public static function fromXmlArray(array $data): self
    {
        return new self(
            opisInnegoLadunku: new OpisInnegoLadunku($data['OpisInnegoLadunku']),
            ladunekInny: isset($data['LadunekInny']) ? LadunekInny::from($data['LadunekInny']) : LadunekInny::Default,
        );
    }
}
