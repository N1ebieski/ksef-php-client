<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\Rola;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class RolaGroup extends AbstractDTO implements DomSerializableInterface
{
    public function __construct(
        public Rola $rola,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $rolaGroup = $dom->createElement('RolaGroup');
        $dom->appendChild($rolaGroup);

        $rola = $dom->createElement('Rola');
        $rola->appendChild($dom->createTextNode((string) $this->rola->value));

        $rolaGroup->appendChild($rola);

        return $dom;
    }
}
