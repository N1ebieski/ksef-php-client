<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\StopkaFaktury;
use N1ebieski\KSEFClient\Requests\Online\ValueObjects\XmlNamespace;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class Informacje extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @return void
     */
    public function __construct(
        public ?StopkaFaktury $stopkaFaktury = null,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $informacje = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'Informacje');
        $dom->appendChild($informacje);

        if ($this->stopkaFaktury instanceof StopkaFaktury) {
            $stopkaFaktury = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'StopkaFaktury');
            $stopkaFaktury->appendChild($dom->createTextNode((string) $this->stopkaFaktury));
            $informacje->appendChild($stopkaFaktury);
        }

        return $dom;
    }
}
