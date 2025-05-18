<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\OpisPlatnosci;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\PlatnoscInna;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class PlatnoscInnaGroup extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param PlatnoscInna $platnoscInna Znacznik innej formy płatności: 1 - inna forma płatności
     * @param OpisPlatnosci $opisPlatnosci Opis płatnosci Doprecyzowanie innej formy płatnośc
     * @return void
     */
    public function __construct(
        public OpisPlatnosci $opisPlatnosci,
        public PlatnoscInna $platnoscInna = PlatnoscInna::Default,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $platnoscInnaGroup = $dom->createElement('PlatnoscInnaGroup');
        $dom->appendChild($platnoscInnaGroup);

        $platnoscInna = $dom->createElement('PlatnoscInna');
        $platnoscInna->appendChild($dom->createTextNode((string) $this->platnoscInna->value));

        $platnoscInnaGroup->appendChild($platnoscInna);

        $opisPlatnosci = $dom->createElement('OpisPlatnosci');
        $opisPlatnosci->appendChild($dom->createTextNode((string) $this->opisPlatnosci));

        $platnoscInnaGroup->appendChild($opisPlatnosci);

        return $dom;
    }
}
