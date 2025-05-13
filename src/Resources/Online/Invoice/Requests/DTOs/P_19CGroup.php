<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\KodKraju;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\KodUE;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\Nazwa;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\NrID;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\NrVatUE;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_19;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_19A;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_19B;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_19C;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_19N;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_6;
use N1ebieski\KSEFClient\Resources\Online\ValueObjects\XmlNamespace;
use N1ebieski\KSEFClient\Support\DTO;
use N1ebieski\KSEFClient\ValueObjects\NIP;

final readonly class P_19CGroup extends DTO implements DomSerializableInterface
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
