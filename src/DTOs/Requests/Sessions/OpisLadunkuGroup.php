<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\OpisLadunku;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final class OpisLadunkuGroup extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    /**
     * @param OpisLadunku $opisLadunku Rodzaj ładunku
     */
    public function __construct(
        public readonly OpisLadunku $opisLadunku
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $opisLadunkuGroup = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'OpisLadunkuGroup');
        $dom->appendChild($opisLadunkuGroup);

        $opisLadunku = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'OpisLadunku');
        $opisLadunku->appendChild($dom->createTextNode((string) $this->opisLadunku->value));

        $opisLadunkuGroup->appendChild($opisLadunku);

        return $dom;
    }

    public static function fromXmlArray(array $data): self
    {
        return new self(opisLadunku: OpisLadunku::from($data['OpisLadunku']));
    }
}
