<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use DOMElement;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class NoweSrodkiTransportu extends AbstractDTO implements DomSerializableInterface
{
    public function __construct(
        public P_22Group | P_22NGroup $p_22Group = new P_22NGroup(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $noweSrodkiTransportu = $dom->createElement('NoweSrodkiTransportu');
        $dom->appendChild($noweSrodkiTransportu);

        /** @var DOMElement $p_22Group */
        $p_22Group = $this->p_22Group->toDom()->documentElement;

        foreach ($p_22Group->childNodes as $child) {
            $noweSrodkiTransportu->appendChild($dom->importNode($child, true));
        }

        $dom->appendChild($noweSrodkiTransportu);

        return $dom;
    }
}
