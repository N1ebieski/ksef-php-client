<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\WartoscZamowienia;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Validator\Rules\Array\MaxRule;
use N1ebieski\KSEFClient\Validator\Rules\Array\MinRule;
use N1ebieski\KSEFClient\Validator\Validator;

final class Zamowienie extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    /**
     * @var array<int, ZamowienieWiersz>
     */
    public readonly array $zamowienieWiersz;

    /**
     * @param WartoscZamowienia $wartoscZamowienia Wartość zamówienia lub umowy z uwzględnieniem kwoty podatku
     * @param array<int, ZamowienieWiersz> $zamowienieWiersz Szczegółowe pozycje zamówienia lub umowy w walucie, w której wystawiono fakturę zaliczkową
     */
    public function __construct(
        public readonly WartoscZamowienia $wartoscZamowienia,
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

        $zamowienie = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Zamowienie');
        $dom->appendChild($zamowienie);

        $wartoscZamowienia = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'WartoscZamowienia');
        $wartoscZamowienia->appendChild($dom->createTextNode((string) $this->wartoscZamowienia));

        $zamowienie->appendChild($wartoscZamowienia);

        foreach ($this->zamowienieWiersz as $zamowienieWiersz) {
            $zamowienieWiersz = $dom->importNode($zamowienieWiersz->toDom()->documentElement, true);

            $zamowienie->appendChild($zamowienieWiersz);
        }

        return $dom;
    }

    public static function fromXmlArray(array $data): self
    {
        return new self(
            wartoscZamowienia: new WartoscZamowienia($data['WartoscZamowienia']),
            zamowienieWiersz: array_map(
                fn (array $item) => ZamowienieWiersz::fromXmlArray($item),
                self::ensureList($data['ZamowienieWiersz'])
            ),
        );
    }
}
