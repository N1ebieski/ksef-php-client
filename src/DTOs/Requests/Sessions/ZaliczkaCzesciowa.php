<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\XmlNormalizableInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\KursWalutyZW;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_15Z;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_6Z;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class ZaliczkaCzesciowa extends AbstractDTO implements DomSerializableInterface, XmlNormalizableInterface
{
    /**
     * @param P_6Z $p_6Z Data otrzymania płatności, o której mowa w art. 106b ust. 1 pkt 4 ustawy
     * @param P_15Z $p_15Z Kwota płatności, o której mowa w art. 106b ust. 1 pkt 4 ustawy, składająca się na kwotę w polu P_15. W przypadku faktur korygujących korekta kwoty wynikającej z faktury korygowanej
     * @param Optional|KursWalutyZW $kursWalutyZW Kurs waluty stosowany do wyliczenia kwoty podatku w przypadkach, o których mowa w Dziale VI ustawy
     */
    public function __construct(
        public readonly P_6Z $p_6Z,
        public readonly P_15Z $p_15Z,
        public readonly Optional | KursWalutyZW $kursWalutyZW = new Optional()
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $zaliczkaCzesciowa = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'ZaliczkaCzesciowa');
        $dom->appendChild($zaliczkaCzesciowa);

        $p_6Z = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_6Z');
        $p_6Z->appendChild($dom->createTextNode((string) $this->p_6Z));

        $zaliczkaCzesciowa->appendChild($p_6Z);

        $p_15Z = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_15Z');
        $p_15Z->appendChild($dom->createTextNode((string) $this->p_15Z));

        $zaliczkaCzesciowa->appendChild($p_15Z);

        if ($this->kursWalutyZW instanceof KursWalutyZW) {
            $kursWalutyZW = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'KursWalutyZW');
            $kursWalutyZW->appendChild($dom->createTextNode((string) $this->kursWalutyZW));

            $zaliczkaCzesciowa->appendChild($kursWalutyZW);
        }

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        return Arr::only($data, ['P_6Z', 'P_15Z', 'KursWalutyZW']);
    }
}
