<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\FormaPlatnosci;
use N1ebieski\KSEFClient\Requests\Online\ValueObjects\XmlNamespace;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class FormaPlatnosciGroup extends AbstractDTO implements DomSerializableInterface
{
    public function __construct(
        public FormaPlatnosci $formaPlatnosci,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $formaPlatnosciGroup = $dom->createElement('FormaPlatnosciGroup');
        $dom->appendChild($formaPlatnosciGroup);

        $formaPlatnosci = $dom->createElement('FormaPlatnosci');
        $formaPlatnosci->appendChild($dom->createTextNode((string) $this->formaPlatnosci->value));

        $formaPlatnosciGroup->appendChild($formaPlatnosci);

        $dom->appendChild($formaPlatnosciGroup);

        return $dom;
    }
}
