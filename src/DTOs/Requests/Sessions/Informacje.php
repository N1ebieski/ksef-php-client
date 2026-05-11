<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\StopkaFaktury;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;

final class Informacje extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    public function __construct(
        public readonly Optional | StopkaFaktury $stopkaFaktury = new Optional(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $informacje = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Informacje');
        $dom->appendChild($informacje);

        if ($this->stopkaFaktury instanceof StopkaFaktury) {
            $stopkaFaktury = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'StopkaFaktury');
            $stopkaFaktury->appendChild($dom->createTextNode((string) $this->stopkaFaktury));
            $informacje->appendChild($stopkaFaktury);
        }

        return $dom;
    }

    public static function fromXmlArray(array $data): self
    {
        return new self(
            stopkaFaktury: isset($data['StopkaFaktury']) ? new StopkaFaktury($data['StopkaFaktury']) : new Optional(),
        );
    }
}
