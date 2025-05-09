<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\Email;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\Telefon;
use N1ebieski\KSEFClient\Support\DTO;

final readonly class DaneKontaktowe extends DTO implements DomSerializableInterface
{
    public function __construct(
        public ?Email $email = null,
        public ?Telefon $telefon = null
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $daneKontaktowe = $dom->createElement('DaneKontaktowe');
        $dom->appendChild($daneKontaktowe);

        if ($this->email instanceof Email) {
            $email = $dom->createElement('Email');
            $email->appendChild($dom->createTextNode((string) $this->email));
            $daneKontaktowe->appendChild($email);
        }

        if ($this->telefon instanceof Telefon) {
            $telefon = $dom->createElement('Telefon');
            $telefon->appendChild($dom->createTextNode((string) $this->telefon));
            $daneKontaktowe->appendChild($telefon);
        }

        return $dom;
    }
}
