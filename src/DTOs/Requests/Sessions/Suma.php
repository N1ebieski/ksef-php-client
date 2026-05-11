<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\SKom;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Validator\Rules\Array\MaxRule;
use N1ebieski\KSEFClient\Validator\Rules\Array\MinRule;
use N1ebieski\KSEFClient\Validator\Validator;

final class Suma extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    /**
     * @var array<int, SKom>
     */
    public readonly array $sKom;

    /**
     * @param array<int, SKom> $sKom Zawartość pola
     */
    public function __construct(
        array $sKom,
    ) {
        Validator::validate([
            'sKom' => $sKom,
        ], [
            'sKom' => [new MinRule(1), new MaxRule(20)],
        ]);

        $this->sKom = $sKom;
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $suma = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Suma');
        $dom->appendChild($suma);

        foreach ($this->sKom as $sKom) {
            $_sKom = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'SKom');
            $_sKom->appendChild($dom->createTextNode((string) $sKom));

            $suma->appendChild($_sKom);
        }

        return $dom;
    }

    public static function fromXmlArray(array $data): self
    {
        return new self(
            sKom: array_map(
                fn (string $value) => new SKom($value),
                self::ensureList($data['sKom'])
            ),
        );
    }
}
