<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\KursWalutyZK;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_15ZK;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;

final class P_15ZKGroup extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    /**
     * @param P_15ZK $p15ZK W przypadku korekt faktur zaliczkowych, kwota zapłaty przed korektą. W przypadku korekt faktur, o których mowa w art. 106f ust. 3 ustawy, kwota pozostała do zapłaty przed korektą
     * @param Optional|KursWalutyZK $kursWalutyZK Kurs waluty stosowany do wyliczenia kwoty podatku w przypadkach, o których mowa w Dziale VI ustawy przed korektą
     */
    public function __construct(
        public readonly P_15ZK $p15ZK,
        public readonly Optional | KursWalutyZK $kursWalutyZK = new Optional(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p15ZKGroup = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_15ZKGroup');
        $dom->appendChild($p15ZKGroup);

        $p15ZK = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_15ZK');
        $p15ZK->appendChild($dom->createTextNode((string) $this->p15ZK));

        $p15ZKGroup->appendChild($p15ZK);

        if ($this->kursWalutyZK instanceof KursWalutyZK) {
            $kursWalutyZK = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'KursWalutyZK');
            $kursWalutyZK->appendChild($dom->createTextNode((string) $this->kursWalutyZK));

            $p15ZKGroup->appendChild($kursWalutyZK);
        }

        return $dom;
    }

    public static function fromXmlArray(array $data): self
    {
        return new self(
            p15ZK: new P_15ZK($data['P_15ZK']),
            kursWalutyZK: isset($data['KursWalutyZK']) ? new KursWalutyZK($data['KursWalutyZK']) : new Optional(),
        );
    }
}
