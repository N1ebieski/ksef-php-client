<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\BDO;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\KRS;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\PelnaNazwa;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\REGON;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class Rejestry extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    /**
     * @param KRS|Optional $krs Numer Krajowego Rejestru Sądowego
     * @param BDO|Optional $bdo Numer w Bazie Danych o Odpadach
     */
    public function __construct(
        public readonly Optional | PelnaNazwa $pelnaNazwa = new Optional(),
        public readonly Optional | KRS $krs = new Optional(),
        public readonly Optional | REGON $regon = new Optional(),
        public readonly Optional | BDO $bdo = new Optional(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $rejestry = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Rejestry');
        $dom->appendChild($rejestry);

        if ($this->pelnaNazwa instanceof PelnaNazwa) {
            $pelnaNazwa = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'PelnaNazwa');
            $pelnaNazwa->appendChild($dom->createTextNode((string) $this->pelnaNazwa));
            $rejestry->appendChild($pelnaNazwa);
        }

        if ($this->krs instanceof KRS) {
            $krs = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'KRS');
            $krs->appendChild($dom->createTextNode((string) $this->krs));
            $rejestry->appendChild($krs);
        }

        if ($this->regon instanceof REGON) {
            $regon = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'REGON');
            $regon->appendChild($dom->createTextNode((string) $this->regon));
            $rejestry->appendChild($regon);
        }

        if ($this->bdo instanceof BDO) {
            $bdo = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'BDO');
            $bdo->appendChild($dom->createTextNode((string) $this->bdo));
            $rejestry->appendChild($bdo);
        }

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        $data['krs'] = $data['KRS'] ?? new Optional();
        $data['regon'] = $data['REGON'] ?? new Optional();
        $data['bdo'] = $data['BDO'] ?? new Optional();

        return Arr::onlyClassParameters($data, self::class);
    }
}
