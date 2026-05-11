<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\RodzajTransportu;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class RodzajTransportuGroup extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    /**
     * @param RodzajTransportu $rodzajTransportu Rodzaj zastosowanego transportu w przypadku dokonanej dostawy towarów
     */
    public function __construct(
        public readonly RodzajTransportu $rodzajTransportu
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $rodzajTransportuGroup = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'RodzajTransportuGroup');
        $dom->appendChild($rodzajTransportuGroup);

        $rodzajTransportu = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'RodzajTransportu');
        $rodzajTransportu->appendChild($dom->createTextNode((string) $this->rodzajTransportu->value));

        $rodzajTransportuGroup->appendChild($rodzajTransportu);

        return $dom;
    }

    public static function fromXmlArray(array $data): self
    {
        return new self(rodzajTransportu: RodzajTransportu::from($data['rodzajTransportu']));
    }
}
