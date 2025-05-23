<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\KursUmowny;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\WalutaUmowna;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class WalutaUmownaGroup extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param KursUmowny $kursUmowny Kurs umowny - w przypadkach, gdy na fakturze znajduje się informacja o kursie, po którym zostały przeliczone kwoty wykazane na fakturze w złotych. Nie dotyczy przypadków, o których mowa w Dziale VI ustawy
     * @param WalutaUmowna $walutaUmowna Waluta umowna - trzyliterowy kod waluty (ISO-4217) w przypadkach, gdy na fakturze znajduje się informacja o kursie, po którym zostały przeliczone kwoty wykazane na fakturze w złotych. Nie dotyczy przypadków, o których mowa w Dziale VI ustawy
     */
    public function __construct(
        public KursUmowny $kursUmowny,
        public WalutaUmowna $walutaUmowna
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $walutaUmownaGroup = $dom->createElement('WalutaUmownaGroup');
        $dom->appendChild($walutaUmownaGroup);

        $kursUmowny = $dom->createElement('KursUmowny');
        $kursUmowny->appendChild($dom->createTextNode((string) $this->kursUmowny));

        $walutaUmownaGroup->appendChild($kursUmowny);

        $walutaUmowna = $dom->createElement('WalutaUmowna');
        $walutaUmowna->appendChild($dom->createTextNode((string) $this->walutaUmowna));

        $walutaUmownaGroup->appendChild($walutaUmowna);

        return $dom;
    }
}
