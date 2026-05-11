<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\WKom;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Validator\Rules\Array\MaxRule;
use N1ebieski\KSEFClient\Validator\Rules\Array\MinRule;
use N1ebieski\KSEFClient\Validator\Validator;

final class Wiersz extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    /**
     * @var array<int, WKom>
     */
    public readonly array $wKom;

    /**
     * @param array<int, WKom> $wKom Zawartość pola
     */
    public function __construct(
        array $wKom,
    ) {
        Validator::validate([
            'wKom' => $wKom,
        ], [
            'wKom' => [new MinRule(1), new MaxRule(20)],
        ]);

        $this->wKom = $wKom;
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $wiersz = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Wiersz');
        $dom->appendChild($wiersz);

        foreach ($this->wKom as $wKom) {
            $_wKom = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'WKom');
            $_wKom->appendChild($dom->createTextNode((string) $wKom));

            $wiersz->appendChild($_wKom);
        }

        return $dom;
    }

    public static function fromXmlArray(array $data): self
    {
        return new self(
            wKom: array_map(
                fn (string $value) => new WKom($value),
                self::ensureList($data['WKom'])
            ),
        );
    }
}
