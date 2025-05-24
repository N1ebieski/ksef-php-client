<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\Validator\Rules\Array\MaxRule;
use N1ebieski\KSEFClient\Validator\Validator;

final readonly class Stopka extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @var Optional|array<int, Informacje>
     */
    public Optional | array $informacje;

    /**
     * @var Optional|array<int, Rejestry>
     */
    public Optional | array $rejestry;

    /**
     * @param Optional|array<int, Informacje> $informacje Pozostałe dane
     * @param Optional|array<int, Rejestry> $rejestry
     * @return void
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

        $stopka = $dom->createElement('Stopka');
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
}
