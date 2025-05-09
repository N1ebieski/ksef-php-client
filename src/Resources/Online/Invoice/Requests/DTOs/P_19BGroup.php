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
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_19N;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_6;
use N1ebieski\KSEFClient\Support\DTO;
use N1ebieski\KSEFClient\ValueObjects\NIP;

final readonly class P_19BGroup extends DTO implements DomSerializableInterface
{
    /**
     * @param P_19B $p_19b Jeśli pole P_19 równa się "1" - należy wskazać przepis dyrektywy 2006/112/WE, który zwalnia od podatku taką dostawę towarów lub takie świadczenie usług
     * @return void
     */
    public function __construct(
        public P_19B $p_19b,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_19bgroup = $dom->createElement('P_19BGroup');
        $dom->appendChild($p_19bgroup);

        $p_19b = $dom->createElement('P_19N');
        $p_19b->appendChild($dom->createTextNode((string) $this->p_19b));

        $p_19bgroup->appendChild($p_19b);

        return $dom;
    }
}
