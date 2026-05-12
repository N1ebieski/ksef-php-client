<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\KursUmowny;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\WalutaUmowna;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class WalutaUmownaGroup extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    /**
     * @param KursUmowny $kursUmowny Kurs umowny - w przypadkach, gdy na fakturze znajduje się informacja o kursie, po którym zostały przeliczone kwoty wykazane na fakturze w złotych. Nie dotyczy przypadków, o których mowa w Dziale VI ustawy
     * @param WalutaUmowna $walutaUmowna Waluta umowna - trzyliterowy kod waluty (ISO-4217) w przypadkach, gdy na fakturze znajduje się informacja o kursie, po którym zostały przeliczone kwoty wykazane na fakturze w złotych. Nie dotyczy przypadków, o których mowa w Dziale VI ustawy
     */
    public function __construct(
        public readonly KursUmowny $kursUmowny,
        public readonly WalutaUmowna $walutaUmowna
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $walutaUmownaGroup = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'WalutaUmownaGroup');
        $dom->appendChild($walutaUmownaGroup);

        $kursUmowny = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'KursUmowny');
        $kursUmowny->appendChild($dom->createTextNode((string) $this->kursUmowny));

        $walutaUmownaGroup->appendChild($kursUmowny);

        $walutaUmowna = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'WalutaUmowna');
        $walutaUmowna->appendChild($dom->createTextNode((string) $this->walutaUmowna));

        $walutaUmownaGroup->appendChild($walutaUmowna);

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        return Arr::onlyClassParameters($data, self::class);
    }
}
