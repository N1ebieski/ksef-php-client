<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Resources\Online\ValueObjects\XmlNamespace;
use N1ebieski\KSEFClient\Support\DTO;

final readonly class WarunkiTransakcji extends DTO implements DomSerializableInterface
{
    /**
     * @param array<int, Zamowienia> $zamowienia
     * @return void
     */
    public function __construct(
        public array $zamowienia = []
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $warunkiTransakcji = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'WarunkiTransakcji');
        $dom->appendChild($warunkiTransakcji);

        foreach ($this->zamowienia as $zamowienie) {
            $zamowienie = $dom->importNode($zamowienie->toDom()->documentElement, true);
            $warunkiTransakcji->appendChild($zamowienie);
        }

        return $dom;
    }
}
