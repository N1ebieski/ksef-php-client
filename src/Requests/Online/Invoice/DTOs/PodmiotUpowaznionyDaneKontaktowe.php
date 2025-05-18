<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\EmailPU;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\TelefonPU;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class PodmiotUpowaznionyDaneKontaktowe extends AbstractDTO implements DomSerializableInterface
{
    public function __construct(
        public ?EmailPU $emailPU = null,
        public ?TelefonPU $telefonPU = null
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $daneKontaktowe = $dom->createElement('DaneKontaktowe');
        $dom->appendChild($daneKontaktowe);

        if ($this->emailPU instanceof EmailPU) {
            $emailPU = $dom->createElement('EmailPU');
            $emailPU->appendChild($dom->createTextNode((string) $this->emailPU));
            $daneKontaktowe->appendChild($emailPU);
        }

        if ($this->telefonPU instanceof TelefonPU) {
            $telefonPU = $dom->createElement('TelefonPU');
            $telefonPU->appendChild($dom->createTextNode((string) $this->telefonPU));
            $daneKontaktowe->appendChild($telefonPU);
        }

        return $dom;
    }
}
