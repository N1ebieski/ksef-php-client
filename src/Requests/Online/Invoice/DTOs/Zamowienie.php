<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\WartoscZamowienia;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Validator\Rules\Array\MaxRule;
use N1ebieski\KSEFClient\Validator\Rules\Array\MinRule;
use N1ebieski\KSEFClient\Validator\Validator;

final readonly class Zamowienie extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @var array<int, ZamowienieWiersz>
     */
    public array $zamowienieWiersz;

    /**
     * @param WartoscZamowienia $wartoscZamowienia Wartość zamówienia lub umowy z uwzględnieniem kwoty podatku
     * @param array<int, ZamowienieWiersz> $zamowienieWiersz Szczegółowe pozycje zamówienia lub umowy w walucie, w której wystawiono fakturę zaliczkową
     */
    public function __construct(
        public WartoscZamowienia $wartoscZamowienia,
        array $zamowienieWiersz
    ) {
        Validator::validate([
            'zamowienieWiersz' => $zamowienieWiersz
        ], [
            'zamowienieWiersz' => [new MinRule(1), new MaxRule(10000)]
        ]);

        $this->zamowienieWiersz = $zamowienieWiersz;
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $zamowienie = $dom->createElement('Zamowienie');
        $dom->appendChild($zamowienie);

        $wartoscZamowienia = $dom->createElement('WartoscZamowienia');
        $wartoscZamowienia->appendChild($dom->createTextNode((string) $this->wartoscZamowienia));

        $zamowienie->appendChild($wartoscZamowienia);

        foreach ($this->zamowienieWiersz as $zamowienieWiersz) {
            $zamowienieWiersz = $dom->importNode($zamowienieWiersz->toDom()->documentElement, true);

            $zamowienie->appendChild($zamowienieWiersz);
        }

        return $dom;
    }
}
