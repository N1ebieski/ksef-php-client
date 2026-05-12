<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_22N;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class P_22NGroup extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    /**
     * @param P_22N $p_22N Znacznik braku wewnątrzwspólnotowej dostawy nowych środków transportu
     */
    public function __construct(
        public readonly P_22N $p_22N = P_22N::Default,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_22NGroup = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_22NGroup');
        $dom->appendChild($p_22NGroup);

        $p_22N = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_22N');
        $p_22N->appendChild($dom->createTextNode((string) $this->p_22N->value));

        $p_22NGroup->appendChild($p_22N);

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        return Arr::onlyClassParameters($data, self::class);
    }
}
