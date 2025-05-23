<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use DOMElement;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\JednostkaOpakowania;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;

final readonly class LadunekGroup extends AbstractDTO implements DomSerializableInterface
{
    public function __construct(
        public OpisLadunkuGroup | LadunekInnyGroup $opisLadunkuGroup,
        public Optional | JednostkaOpakowania $jednostkaOpakowania = new Optional(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $ladunekGroup = $dom->createElement('LadunekGroup');
        $dom->appendChild($ladunekGroup);

        /** @var DOMElement $opisLadunkuGroup */
        $opisLadunkuGroup = $dom->importNode($this->opisLadunkuGroup->toDom()->documentElement, true);

        foreach ($opisLadunkuGroup->childNodes as $child) {
            $ladunekGroup->appendChild($dom->importNode($child, true));
        }

        if ($this->jednostkaOpakowania instanceof JednostkaOpakowania) {
            $jednostkaOpakowania = $dom->createElement('JednostkaOpakowania');
            $jednostkaOpakowania->appendChild($dom->createTextNode((string) $this->jednostkaOpakowania));

            $ladunekGroup->appendChild($jednostkaOpakowania);
        }

        return $dom;
    }
}
