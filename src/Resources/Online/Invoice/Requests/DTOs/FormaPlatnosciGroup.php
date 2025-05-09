<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\DataZamowienia;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\DataZaplaty;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\FormaPlatnosci;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\NrZamowienia;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\Zaplacono;
use N1ebieski\KSEFClient\Support\DTO;

final readonly class FormaPlatnosciGroup extends DTO implements DomSerializableInterface
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
