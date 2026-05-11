<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\DoRozliczenia;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final class DoRozliczeniaGroup extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    /**
     * @param DoRozliczenia $doRozliczenia Kwota nadpłacona do rozliczenia/zwrotu
     */
    public function __construct(
        public readonly DoRozliczenia $doRozliczenia,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $doRozliczeniaGroup = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'DoRozliczeniaGroup');
        $dom->appendChild($doRozliczeniaGroup);

        $doRozliczenia = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'DoRozliczenia');
        $doRozliczenia->appendChild($dom->createTextNode($this->doRozliczenia->value));

        $doRozliczeniaGroup->appendChild($doRozliczenia);

        return $dom;
    }

    public static function fromXmlArray(array $data): self
    {
        return new self(doRozliczenia: new DoRozliczenia($data['DoRozliczenia']));
    }
}
