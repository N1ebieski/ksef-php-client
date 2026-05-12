<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_6_Do;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_6_Od;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class OkresFa extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    /**
     * @param P_6_Od $p_6_Od Data początkowa okresu, którego dotyczy faktura
     * @param P_6_Do $p_6_Do Data końcowa okresu, którego dotyczy faktura - data dokonania lub zakończenia dostawy towarów lub wykonania usługi
     */
    public function __construct(
        public readonly P_6_Od $p_6_Od,
        public readonly P_6_Do $p_6_Do
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $okresFa = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'OkresFa');
        $dom->appendChild($okresFa);

        $p_6_Od = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_6_Od');
        $p_6_Od->appendChild($dom->createTextNode((string) $this->p_6_Od));

        $okresFa->appendChild($p_6_Od);

        $p_6_Do = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_6_Do');
        $p_6_Do->appendChild($dom->createTextNode((string) $this->p_6_Do));

        $okresFa->appendChild($p_6_Do);

        $dom->appendChild($okresFa);

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        return Arr::onlyClassParameters($data, self::class);
    }
}
