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

final class Zalacznik extends AbstractDTO implements DomSerializableInterface, XmlNormalizableInterface
{
    /**
     * @var array<int, BlokDanych>
     */
    public readonly array $blokDanych;

    /**
     * @param array<int, BlokDanych> $blokDanych Szczegółowe dane załącznika do faktury (bloki danych)
     */
    public function __construct(
        array $blokDanych,
    ) {
        Validator::validate([
            'blokDanych' => $blokDanych,
        ], [
            'blokDanych' => [new MinRule(1), new MaxRule(1000)],
        ]);

        $this->blokDanych = $blokDanych;
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $zalacznik = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Zalacznik');
        $dom->appendChild($zalacznik);

        foreach ($this->blokDanych as $blokDanych) {
            $blokDanych = $dom->importNode($blokDanych->toDom()->documentElement, true);

            $zalacznik->appendChild($blokDanych);
        }

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        $data['BlokDanych'] = array_map(
            fn (array $item): array => BlokDanych::normalizeXmlArray($item),
            Arr::ensureList($data['BlokDanych'])
        );

        return Arr::only($data, ['BlokDanych']);
    }
}
