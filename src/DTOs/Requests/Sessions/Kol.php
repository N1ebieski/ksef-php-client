<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\NKom;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\Typ;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class Kol extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    /**
     * @param Typ $typ Typ danych w nagłówku tabeli
     * @param NKom $nKom Zawartość pola
     */
    public function __construct(
        public readonly Typ $typ,
        public readonly NKom $nKom
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $kol = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Kol');
        $kol->setAttribute('Typ', (string) $this->typ->value);

        $dom->appendChild($kol);

        $nKom = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'NKom');
        $nKom->appendChild($dom->createTextNode((string) $this->nKom));

        $kol->appendChild($nKom);

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        $data['typ'] = $data['@attributes']['Typ'];

        return Arr::onlyClassParameters($data, self::class);
    }
}
