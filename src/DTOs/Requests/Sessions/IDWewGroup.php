<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\IDWew;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final class IDWewGroup extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    /**
     * @param IDWew $iDWew Identyfikator wewnętrzny z NIP
     */
    public function __construct(
        public readonly IDWew $iDWew,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $iDWewGroup = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'IDWewGroup');
        $dom->appendChild($iDWewGroup);

        $iDWew = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'IDWew');
        $iDWew->appendChild($dom->createTextNode($this->iDWew->value));

        $iDWewGroup->appendChild($iDWew);

        return $dom;
    }

    public static function fromXmlArray(array $data): self
    {
        return new self(iDWew: new IDWew($data['IDWew']));
    }
}
