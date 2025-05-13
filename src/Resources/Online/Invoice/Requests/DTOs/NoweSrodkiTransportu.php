<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs;

use DOMDocument;
use DOMElement;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_22N;
use N1ebieski\KSEFClient\Resources\Online\ValueObjects\XmlNamespace;
use N1ebieski\KSEFClient\Support\DTO;

final readonly class NoweSrodkiTransportu extends DTO implements DomSerializableInterface
{
    public function __construct(
        public P_22Group | P_22NGroup $p_22group = new P_22NGroup(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $noweSrodkiTransportu = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'NoweSrodkiTransportu');
        $dom->appendChild($noweSrodkiTransportu);

        /** @var DOMElement $p_22group */
        $p_22group = $this->p_22group->toDom()->documentElement;

        foreach ($p_22group->childNodes as $child) {
            $noweSrodkiTransportu->appendChild($dom->importNode($child, true));
        }

        $dom->appendChild($noweSrodkiTransportu);

        return $dom;
    }
}
