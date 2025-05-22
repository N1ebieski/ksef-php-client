<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use DOMElement;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;

final readonly class Platnosc extends AbstractDTO implements DomSerializableInterface
{
    public function __construct(
        public Optional | ZaplaconoGroup $zaplatagroup = new Optional(),
        public Optional | FormaPlatnosciGroup | PlatnoscInnaGroup $platnoscgroup = new Optional(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $platnosc = $dom->createElement('Platnosc');
        $dom->appendChild($platnosc);

        if ($this->zaplatagroup instanceof ZaplaconoGroup) {
            /** @var DOMElement $zaplatagroup */
            $zaplatagroup = $this->zaplatagroup->toDom()->documentElement;

            foreach ($zaplatagroup->childNodes as $child) {
                $platnosc->appendChild($dom->importNode($child, true));
            }
        }

        if ( ! $this->platnoscgroup instanceof Optional) {
            /** @var DOMElement $platnoscgroup */
            $platnoscgroup = $this->platnoscgroup->toDom()->documentElement;

            foreach ($platnoscgroup->childNodes as $child) {
                $platnosc->appendChild($dom->importNode($child, true));
            }
        }

        return $dom;
    }
}
