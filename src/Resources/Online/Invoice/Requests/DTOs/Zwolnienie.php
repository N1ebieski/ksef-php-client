<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_19N;
use N1ebieski\KSEFClient\Resources\Online\ValueObjects\XmlNamespace;
use N1ebieski\KSEFClient\Support\DTO;

final readonly class Zwolnienie extends DTO implements DomSerializableInterface
{
    public function __construct(
        public P_19Group | P_19NGroup $p_19group = new P_19NGroup(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $zwolnienie = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'Zwolnienie');
        $dom->appendChild($zwolnienie);

        $p_19group = $this->p_19group->toDom()->documentElement;

        foreach ($p_19group->childNodes as $child) {
            $zwolnienie->appendChild($dom->importNode($child, true));
        }

        $dom->appendChild($zwolnienie);

        return $dom;
    }
}
