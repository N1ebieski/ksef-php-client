<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\Email;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\Telefon;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;

final class DaneKontaktowe extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    public function __construct(
        public readonly Optional | Email $email = new Optional(),
        public readonly Optional | Telefon $telefon = new Optional()
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $daneKontaktowe = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'DaneKontaktowe');
        $dom->appendChild($daneKontaktowe);

        if ($this->email instanceof Email) {
            $email = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Email');
            $email->appendChild($dom->createTextNode((string) $this->email));
            $daneKontaktowe->appendChild($email);
        }

        if ($this->telefon instanceof Telefon) {
            $telefon = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Telefon');
            $telefon->appendChild($dom->createTextNode((string) $this->telefon));
            $daneKontaktowe->appendChild($telefon);
        }

        return $dom;
    }

    public static function fromXmlArray(array $data): self
    {
        return new self(
            email: isset($data['email']) ? new Email($data['email']) : new Optional(),
            telefon: isset($data['telefon']) ? new Telefon($data['telefon']) : new Optional(),
        );
    }
}
