<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use DOMElement;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class PMarzy extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    public function __construct(
        public readonly P_PMarzyGroup | P_PMarzyNGroup $p_PMarzyGroup = new P_PMarzyNGroup(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $pMarzy = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'PMarzy');
        $dom->appendChild($pMarzy);

        /** @var DOMElement $p_PMarzyGroup */
        $p_PMarzyGroup = $this->p_PMarzyGroup->toDom()->documentElement;

        foreach ($p_PMarzyGroup->childNodes as $child) {
            $pMarzy->appendChild($dom->importNode($child, true));
        }

        $dom->appendChild($pMarzy);

        return $dom;
    }

    public static function fromXmlArray(array $data): self
    {
        $p_PMarzyGroup = isset($data['p_PMarzy'])
            ? P_PMarzyGroup::fromXmlArray($data)
            : P_PMarzyNGroup::fromXmlArray($data);

        return new self(p_PMarzyGroup: $p_PMarzyGroup);
    }
}
