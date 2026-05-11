<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\FormaPlatnosci;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class FormaPlatnosciGroup extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    public function __construct(
        public readonly FormaPlatnosci $formaPlatnosci,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $formaPlatnosciGroup = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'FormaPlatnosciGroup');
        $dom->appendChild($formaPlatnosciGroup);

        $formaPlatnosci = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'FormaPlatnosci');
        $formaPlatnosci->appendChild($dom->createTextNode((string) $this->formaPlatnosci->value));

        $formaPlatnosciGroup->appendChild($formaPlatnosci);

        $dom->appendChild($formaPlatnosciGroup);

        return $dom;
    }

    public static function fromXmlArray(array $data): self
    {
        return new self(formaPlatnosci: FormaPlatnosci::from($data['formaPlatnosci']));
    }
}
