<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use DOMElement;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\ValueObjects\XmlNamespace;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class Platnosc extends AbstractDTO implements DomSerializableInterface
{
    public function __construct(
        public ?ZaplaconoGroup $zaplataGroup = null,
        public FormaPlatnosciGroup | PlatnoscInnaGroup | null $platnoscGroup = null,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $platnosc = $dom->createElement('Platnosc');
        $dom->appendChild($platnosc);

        if ($this->zaplataGroup instanceof ZaplaconoGroup) {
            /** @var DOMElement $zaplataGroup */
            $zaplataGroup = $this->zaplataGroup->toDom()->documentElement;

            foreach ($zaplataGroup->childNodes as $child) {
                $platnosc->appendChild($dom->importNode($child, true));
            }
        }

        if ($this->platnoscGroup !== null) {
            /** @var DOMElement $platnoscGroup */
            $platnoscGroup = $this->platnoscGroup->toDom()->documentElement;

            foreach ($platnoscGroup->childNodes as $child) {
                $platnosc->appendChild($dom->importNode($child, true));
            }
        }

        return $dom;
    }
}
