<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\OpisRoli;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\RolaInna;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class RolaInnaGroup extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param RolaInna $rolaInna Znacznik innego podmiotu: 1 - Inny podmiot
     * @param OpisRoli $opisRoli Opis roli podmiotu - w przypadku wyboru roli jako Inny podmiot
     * @return void
     */
    public function __construct(
        public RolaInna $rolaInna,
        public OpisRoli $opisRoli,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $rolaInnagroup = $dom->createElement('RolaInnaGroup');
        $dom->appendChild($rolaInnagroup);

        $rolaInna = $dom->createElement('RolaInna');
        $rolaInna->appendChild($dom->createTextNode((string) $this->rolaInna->value));

        $rolaInnagroup->appendChild($rolaInna);

        $opisRoli = $dom->createElement('OpisRoli');
        $opisRoli->appendChild($dom->createTextNode((string) $this->opisRoli));

        $rolaInnagroup->appendChild($opisRoli);

        return $dom;
    }
}
