<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\Validator\Rules\Array\MaxRule;
use N1ebieski\KSEFClient\Validator\Rules\Array\MinRule;
use N1ebieski\KSEFClient\Validator\Validator;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\Opis;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class Tabela extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    /**
     * @var Optional|array<int, TMetaDane>
     */
    public readonly Optional | array $tMetaDane;

    /**
     * @var array<int, Wiersz>
     */
    public readonly array $wiersz;

    /**
     * @param TNaglowek $tNaglowek Nagłówek tabeli
     * @param Optional|array<int, TMetaDane> $tMetaDane Dane opisowe dotyczące tabeli
     * @param array<int, Wiersz> $wiersz Wiersze tabeli
     */
    public function __construct(
        public readonly TNaglowek $tNaglowek,
        array $wiersz,
        Optional | array $tMetaDane = new Optional(),
        public readonly Optional | Opis $opis = new Optional(),
        public readonly Optional | Suma $suma = new Optional(),
    ) {
        Validator::validate([
            'tMetaDane' => $tMetaDane,
            'wiersz' => $wiersz,
        ], [
            'tMetaDane' => [new MaxRule(1000)],
            'wiersz' => [new MinRule(1), new MaxRule(1000)],
        ]);

        $this->tMetaDane = $tMetaDane;
        $this->wiersz = $wiersz;
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $tabela = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Tabela');
        $dom->appendChild($tabela);

        if ( ! $this->tMetaDane instanceof Optional) {
            foreach ($this->tMetaDane as $tMetaDane) {
                $tMetaDane = $dom->importNode($tMetaDane->toDom()->documentElement, true);

                $tabela->appendChild($tMetaDane);
            }
        }

        if ($this->opis instanceof Opis) {
            $opis = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Opis');
            $opis->appendChild($dom->createTextNode((string) $this->opis));

            $tabela->appendChild($opis);
        }

        $tNaglowek = $dom->importNode($this->tNaglowek->toDom()->documentElement, true);

        $tabela->appendChild($tNaglowek);

        foreach ($this->wiersz as $wiersz) {
            $wiersz = $dom->importNode($wiersz->toDom()->documentElement, true);

            $tabela->appendChild($wiersz);
        }

        if ($this->suma instanceof Suma) {
            $suma = $dom->importNode($this->suma->toDom()->documentElement, true);

            $tabela->appendChild($suma);
        }

        return $dom;
    }

    public static function fromXmlArray(array $data): self
    {
        return new self(
            tNaglowek: TNaglowek::fromXmlArray($data['tNaglowek']),
            wiersz: array_map(
                fn (array $item) => Wiersz::fromXmlArray($item),
                self::ensureList($data['wiersz'])
            ),
            tMetaDane: isset($data['tMetaDane'])
                ? array_map(
                    fn (array $item) => TMetaDane::fromXmlArray($item),
                    self::ensureList($data['tMetaDane'])
                )
                : new Optional(),
            opis: isset($data['opis']) ? new Opis($data['opis']) : new Optional(),
            suma: isset($data['suma']) ? Suma::fromXmlArray($data['suma']) : new Optional(),
        );
    }
}
