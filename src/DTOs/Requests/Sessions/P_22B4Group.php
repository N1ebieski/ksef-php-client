<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_22B4;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class P_22B4Group extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    /**
     * @param P_22B4 $p_22B4 Jeśli dostawa dotyczy pojazdów lądowych, o których mowa w art. 2 pkt 10 lit. a ustawy - można podać numer ramy
     */
    public function __construct(
        public readonly P_22B4 $p_22B4,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_22B4Group = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_22B4Group');
        $dom->appendChild($p_22B4Group);

        $p_22B4 = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_22B4');
        $p_22B4->appendChild($dom->createTextNode($this->p_22B4->value));

        $p_22B4Group->appendChild($p_22B4);

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        return Arr::onlyClassParameters($data, self::class);
    }
}
