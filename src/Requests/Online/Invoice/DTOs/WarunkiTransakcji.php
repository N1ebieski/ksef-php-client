<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class WarunkiTransakcji extends AbstractDTO implements DomSerializableInterface
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

        $warunkiTransakcji = $dom->createElement('WarunkiTransakcji');
        $dom->appendChild($warunkiTransakcji);

        foreach ($this->zamowienia as $zamowienie) {
            $zamowienie = $dom->importNode($zamowienie->toDom()->documentElement, true);
            $warunkiTransakcji->appendChild($zamowienie);
        }

        return $dom;
    }
}
