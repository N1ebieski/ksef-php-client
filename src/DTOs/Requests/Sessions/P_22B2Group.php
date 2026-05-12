<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_22B2;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class P_22B2Group extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    /**
     * @param P_22B2 $p_22B2 Jeśli dostawa dotyczy pojazdów lądowych, o których mowa w art. 2 pkt 10 lit. a ustawy - można podać numer nadwozia
     */
    public function __construct(
        public readonly P_22B2 $p_22B2,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_22B2Group = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_22B2Group');
        $dom->appendChild($p_22B2Group);

        $p_22B2 = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_22B2');
        $p_22B2->appendChild($dom->createTextNode($this->p_22B2->value));

        $p_22B2Group->appendChild($p_22B2);

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        return Arr::onlyClassParameters($data, self::class);
    }
}
