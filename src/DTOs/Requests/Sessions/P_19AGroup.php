<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_19A;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final class P_19AGroup extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    /**
     * @param P_19A $p_19A Jeśli pole P_19 równa się "1" - należy wskazać przepis ustawy albo aktu wydanego na podstawie ustawy, na podstawie którego podatnik stosuje zwolnienie od podatku
     */
    public function __construct(
        public readonly P_19A $p_19A,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_19AGroup = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_19AGroup');
        $dom->appendChild($p_19AGroup);

        $p_19A = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_19A');
        $p_19A->appendChild($dom->createTextNode((string) $this->p_19A));

        $p_19AGroup->appendChild($p_19A);

        return $dom;
    }

    public static function fromXmlArray(array $data): self
    {
        return new self(p_19A: new P_19A($data['P_19A']));
    }
}
