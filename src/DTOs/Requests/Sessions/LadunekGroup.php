<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use DOMElement;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\JednostkaOpakowania;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class LadunekGroup extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    public function __construct(
        public readonly OpisLadunkuGroup | LadunekInnyGroup $opisLadunkuGroup,
        public readonly Optional | JednostkaOpakowania $jednostkaOpakowania = new Optional(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $ladunekGroup = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'LadunekGroup');
        $dom->appendChild($ladunekGroup);

        /** @var DOMElement $opisLadunkuGroup */
        $opisLadunkuGroup = $dom->importNode($this->opisLadunkuGroup->toDom()->documentElement, true);

        foreach ($opisLadunkuGroup->childNodes as $child) {
            $ladunekGroup->appendChild($dom->importNode($child, true));
        }

        if ($this->jednostkaOpakowania instanceof JednostkaOpakowania) {
            $jednostkaOpakowania = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'JednostkaOpakowania');
            $jednostkaOpakowania->appendChild($dom->createTextNode((string) $this->jednostkaOpakowania));

            $ladunekGroup->appendChild($jednostkaOpakowania);
        }

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        $data['OpisLadunkuGroup'] = match (true) {
            isset($data['LadunekInny']) => LadunekInnyGroup::normalizeXmlArray($data),
            default => OpisLadunkuGroup::normalizeXmlArray($data),
        };

        return Arr::onlyClassParameters($data, self::class);
    }
}
