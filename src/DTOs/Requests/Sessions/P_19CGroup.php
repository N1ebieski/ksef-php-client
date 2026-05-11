<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_19C;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final class P_19CGroup extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    /**
     * @param P_19C $p_19C Jeśli pole P_19 równa się "1" - należy wskazać inną podstawę prawną wskazującą na to, że dostawa towarów lub świadczenie usług korzysta ze zwolnienia od podatku
     */
    public function __construct(
        public readonly P_19C $p_19C,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_19CGroup = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_19CGroup');
        $dom->appendChild($p_19CGroup);

        $p_19C = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_19C');
        $p_19C->appendChild($dom->createTextNode((string) $this->p_19C));

        $p_19CGroup->appendChild($p_19C);

        return $dom;
    }

    public static function fromXmlArray(array $data): self
    {
        return new self(p_19C: new P_19C($data['p_19C']));
    }
}
