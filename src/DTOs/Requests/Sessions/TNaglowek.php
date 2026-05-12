<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\XmlNormalizableInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\Validator\Rules\Array\MaxRule;
use N1ebieski\KSEFClient\Validator\Rules\Array\MinRule;
use N1ebieski\KSEFClient\Validator\Validator;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class TNaglowek extends AbstractDTO implements DomSerializableInterface, XmlNormalizableInterface
{
    /**
     * @var array<int, Kol>
     */
    public readonly array $kol;

    /**
     * @param array<int, Kol> $kol Zawartość pola
     */
    public function __construct(
        array $kol
    ) {
        Validator::validate([
            'kol' => $kol,
        ], [
            'kol' => [new MinRule(1), new MaxRule(20)],
        ]);

        $this->kol = $kol;
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $tNaglowek = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'TNaglowek');
        $dom->appendChild($tNaglowek);

        foreach ($this->kol as $kol) {
            $kol = $dom->importNode($kol->toDom()->documentElement, true);

            $tNaglowek->appendChild($kol);
        }

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        $data['Kol'] = array_map(
            Kol::normalizeXmlArray(...), //@phpstan-ignore-line argument.type
            Arr::ensureList($data['Kol'])
        );

        return Arr::only($data, ['Kol']);
    }
}
