<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\WarunkiDostawy;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;

final readonly class WarunkiTransakcji extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param Optional|array<int, Zamowienia> $zamowienia
     * @param Optional|WarunkiDostawy $warunkiDostawy Warunki dostawy towarów - w przypadku istnienia pomiędzy stronami transakcji, umowy określającej warunki dostawy tzw. Incoterms
     * @return void
     */
    public function __construct(
        public Optional | array $zamowienia = new Optional(),
        public Optional | WarunkiDostawy $warunkiDostawy = new Optional()
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $warunkiTransakcji = $dom->createElement('WarunkiTransakcji');
        $dom->appendChild($warunkiTransakcji);

        if ( ! $this->zamowienia instanceof Optional) {
            foreach ($this->zamowienia as $zamowienie) {
                $zamowienie = $dom->importNode($zamowienie->toDom()->documentElement, true);
                $warunkiTransakcji->appendChild($zamowienie);
            }
        }

        if ($this->warunkiDostawy instanceof WarunkiDostawy) {
            $warunkiDostawy = $dom->createElement('WarunkiDostawy');
            $warunkiDostawy->appendChild($dom->createTextNode((string) $this->warunkiDostawy));

            $warunkiTransakcji->appendChild($warunkiDostawy);
        }

        return $dom;
    }
}
