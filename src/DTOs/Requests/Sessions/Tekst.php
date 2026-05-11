<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\Akapit;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Validator\Rules\Array\MaxRule;
use N1ebieski\KSEFClient\Validator\Rules\Array\MinRule;
use N1ebieski\KSEFClient\Validator\Validator;

final class Tekst extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    /**
     * @var array<int, Akapit>
     */
    public readonly array $akapit;

    /**
     * @param array<int, Akapit> $akapit Akapit stanowiący część tekstową bloku danych
     */
    public function __construct(
        array $akapit,
    ) {
        Validator::validate([
            'akapit' => $akapit,
        ], [
            'akapit' => [new MinRule(1), new MaxRule(10)],
        ]);

        $this->akapit = $akapit;
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $tekst = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Tekst');
        $dom->appendChild($tekst);

        foreach ($this->akapit as $akapit) {
            $_akapit = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Akapit');
            $_akapit->appendChild($dom->createTextNode((string) $akapit));

            $tekst->appendChild($_akapit);
        }

        return $dom;
    }

    public static function fromXmlArray(array $data): self
    {
        return new self(
            akapit: array_map(
                fn (string $value) => new Akapit($value),
                self::ensureList($data['Akapit'])
            ),
        );
    }
}
