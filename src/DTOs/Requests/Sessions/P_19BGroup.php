<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\XmlNormalizableInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_19B;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class P_19BGroup extends AbstractDTO implements DomSerializableInterface, XmlNormalizableInterface
{
    /**
     * @param P_19B $p_19B Jeśli pole P_19 równa się "1" - należy wskazać przepis dyrektywy 2006/112/WE, który zwalnia od podatku taką dostawę towarów lub takie świadczenie usług
     */
    public function __construct(
        public readonly P_19B $p_19B,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_19BGroup = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_19BGroup');
        $dom->appendChild($p_19BGroup);

        $p_19B = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_19B');
        $p_19B->appendChild($dom->createTextNode((string) $this->p_19B));

        $p_19BGroup->appendChild($p_19B);

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        return Arr::only($data, ['P_19B']);
    }
}
