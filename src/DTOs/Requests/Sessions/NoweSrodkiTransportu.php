<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;
use DOMElement;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final class NoweSrodkiTransportu extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    public function __construct(
        public readonly P_22Group | P_22NGroup $p_22Group = new P_22NGroup(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $noweSrodkiTransportu = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'NoweSrodkiTransportu');
        $dom->appendChild($noweSrodkiTransportu);

        /** @var DOMElement $p_22Group */
        $p_22Group = $this->p_22Group->toDom()->documentElement;

        foreach ($p_22Group->childNodes as $child) {
            $noweSrodkiTransportu->appendChild($dom->importNode($child, true));
        }

        $dom->appendChild($noweSrodkiTransportu);

        return $dom;
    }

    public static function fromXmlArray(array $data): self
    {
        $p_22Group = isset($data['P_22']) ? P_22Group::fromXmlArray($data) : P_22NGroup::fromXmlArray($data);

        return new self(p_22Group: $p_22Group);
    }
}
