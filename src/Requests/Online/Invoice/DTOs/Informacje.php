<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\StopkaFaktury;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;

final readonly class Informacje extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @return void
     */
    public function __construct(
        public Optional | StopkaFaktury $stopkaFaktury = new Optional(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $informacje = $dom->createElement('Informacje');
        $dom->appendChild($informacje);

        if ($this->stopkaFaktury instanceof StopkaFaktury) {
            $stopkaFaktury = $dom->createElement('StopkaFaktury');
            $stopkaFaktury->appendChild($dom->createTextNode((string) $this->stopkaFaktury));
            $informacje->appendChild($stopkaFaktury);
        }

        return $dom;
    }
}
