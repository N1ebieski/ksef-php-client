<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions\FakturaRR;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\XmlNormalizableInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\Validator\Rules\Array\MaxRule;
use N1ebieski\KSEFClient\Validator\Validator;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class Stopka extends AbstractDTO implements DomSerializableInterface, XmlNormalizableInterface
{
    /**
     * @var Optional|array<int, Informacje>
     */
    public readonly Optional | array $informacje;

    /**
     * @var Optional|array<int, Rejestry>
     */
    public readonly Optional | array $rejestry;

    /**
     * @param Optional|array<int, Informacje> $informacje Pozostałe dane
     * @param Optional|array<int, Rejestry> $rejestry
     */
    public function __construct(
        Optional | array $informacje = new Optional(),
        Optional | array $rejestry = new Optional()
    ) {
        Validator::validate([
            'informacje' => $informacje,
            'rejestry' => $rejestry
        ], [
            'informacje' => [new MaxRule(3)],
            'rejestry' => [new MaxRule(100)]
        ]);

        $this->informacje = $informacje;
        $this->rejestry = $rejestry;
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $stopka = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'Stopka');
        $dom->appendChild($stopka);

        if ( ! $this->informacje instanceof Optional) {
            foreach ($this->informacje as $informacje) {
                $informacje = $dom->importNode($informacje->toDom()->documentElement, true);
                $stopka->appendChild($informacje);
            }
        }

        if ( ! $this->rejestry instanceof Optional) {
            foreach ($this->rejestry as $rejestry) {
                $rejestry = $dom->importNode($rejestry->toDom()->documentElement, true);
                $stopka->appendChild($rejestry);
            }
        }

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        $data['Informacje'] = match (true) {
            isset($data['Informacje']) => array_map(
                Informacje::normalizeXmlArray(...), //@phpstan-ignore-line argument.type
                Arr::ensureList($data['Informacje'])
            ),
            default => new Optional(),
        };

        $data['Rejestry'] = match (true) {
            isset($data['Rejestry']) => array_map(
                Rejestry::normalizeXmlArray(...), //@phpstan-ignore-line argument.type
                Arr::ensureList($data['Rejestry'])
            ),
            default => new Optional(),
        };

        return Arr::only($data, ['Informacje', 'Rejestry']);
    }
}
