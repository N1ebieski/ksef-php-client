<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\KursUmowny;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\WalutaUmowna;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final class WalutaUmownaGroup extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    /**
     * @param KursUmowny $kursUmowny Kurs umowny
     * @param WalutaUmowna $walutaUmowna Waluta umowna
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

    public static function fromXmlArray(array $data): self
    {
        return new self(
            kursUmowny: new KursUmowny($data['kursUmowny']),
            walutaUmowna: new WalutaUmowna($data['walutaUmowna']),
        );
    }
}
