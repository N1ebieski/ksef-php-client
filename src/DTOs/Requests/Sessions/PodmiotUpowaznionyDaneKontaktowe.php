<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\EmailPU;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\TelefonPU;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;

final class PodmiotUpowaznionyDaneKontaktowe extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    public function __construct(
        public readonly Optional | EmailPU $emailPU = new Optional(),
        public readonly Optional | TelefonPU $telefonPU = new Optional()
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $daneKontaktowe = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'DaneKontaktowe');
        $dom->appendChild($daneKontaktowe);

        if ($this->emailPU instanceof EmailPU) {
            $emailPU = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'EmailPU');
            $emailPU->appendChild($dom->createTextNode((string) $this->emailPU));
            $daneKontaktowe->appendChild($emailPU);
        }

        if ($this->telefonPU instanceof TelefonPU) {
            $telefonPU = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'TelefonPU');
            $telefonPU->appendChild($dom->createTextNode((string) $this->telefonPU));
            $daneKontaktowe->appendChild($telefonPU);
        }

        return $dom;
    }

    public static function fromXmlArray(array $data): self
    {
        return new self(
            emailPU: isset($data['emailPU']) ? new EmailPU($data['emailPU']) : new Optional(),
            telefonPU: isset($data['telefonPU']) ? new TelefonPU($data['telefonPU']) : new Optional(),
        );
    }
}
