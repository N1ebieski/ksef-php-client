<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\DataZamowienia;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\DataZaplaty;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\FormaPlatnosci;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\NrZamowienia;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\OpisPlatnosci;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\PlatnoscInna;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\Zaplacono;
use N1ebieski\KSEFClient\Resources\Online\ValueObjects\XmlNamespace;
use N1ebieski\KSEFClient\Support\DTO;

final readonly class PlatnoscInnaGroup extends DTO implements DomSerializableInterface
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

        $platnoscInnaGroup = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'PlatnoscInnaGroup');
        $dom->appendChild($platnoscInnaGroup);

        $platnoscInna = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'PlatnoscInna');
        $platnoscInna->appendChild($dom->createTextNode((string) $this->platnoscInna->value));

        $platnoscInnaGroup->appendChild($platnoscInna);

        $opisPlatnosci = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'OpisPlatnosci');
        $opisPlatnosci->appendChild($dom->createTextNode((string) $this->opisPlatnosci));

        $platnoscInnaGroup->appendChild($opisPlatnosci);

        return $dom;
    }
}
