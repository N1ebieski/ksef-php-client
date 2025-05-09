<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\BrakID;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\Nazwa;
use N1ebieski\KSEFClient\Support\DTO;
use N1ebieski\KSEFClient\ValueObjects\NIP;

final readonly class Podmiot2DaneIdentyfikacyjne extends DTO implements DomSerializableInterface
{
    public function __construct(
        public NIPGroup | UEGroup | KrajGroup | BrakIDGroup $idgroup,
        public ?Nazwa $nazwa = null
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $daneIdentyfikacyjne = $dom->createElement('DaneIdentyfikacyjne');
        $dom->appendChild($daneIdentyfikacyjne);

        $nipGroup = $this->idgroup->toDom()->documentElement;

        foreach ($nipGroup->childNodes as $child) {
            $daneIdentyfikacyjne->appendChild($dom->importNode($child, true));
        }

        $nazwa = $dom->createElement('Nazwa');
        $nazwa->appendChild($dom->createTextNode((string) $this->nazwa));

        $daneIdentyfikacyjne->appendChild($nazwa);

        return $dom;
    }
}
