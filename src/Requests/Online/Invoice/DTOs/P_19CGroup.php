<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_19C;
use N1ebieski\KSEFClient\Requests\Online\ValueObjects\XmlNamespace;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class P_19CGroup extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param P_19C $p_19c Jeśli pole P_19 równa się "1" - należy wskazać inną podstawę prawną wskazującą na to, że dostawa towarów lub świadczenie usług korzysta ze zwolnienia od podatku
     * @return void
     */
    public function __construct(
        public P_19C $p_19c,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_19cgroup = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'P_19CGroup');
        $dom->appendChild($p_19cgroup);

        $p_19c = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'P_19C');
        $p_19c->appendChild($dom->createTextNode((string) $this->p_19c));

        $p_19cgroup->appendChild($p_19c);

        return $dom;
    }
}
