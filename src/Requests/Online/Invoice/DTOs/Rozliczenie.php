<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use DOMElement;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\SumaObciazen;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\SumaOdliczen;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;

final readonly class Rozliczenie extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param Optional|array<int, Obciazenia> $obciazenia
     * @param Optional|SumaObciazen $sumaObciazen
     * @param Optional|array<int, Odliczenia> $odliczenia
     * @param Optional|SumaOdliczen $sumaOdliczen
     */
    public function __construct(
        public Optional | array $obciazenia = new Optional(),
        public Optional | SumaObciazen $sumaObciazen = new Optional(),
        public Optional | array $odliczenia = new Optional(),
        public Optional | SumaOdliczen $sumaOdliczen = new Optional(),
        public Optional | RozliczenieGroup $rozliczenieGroup = new Optional()
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $rozliczenie = $dom->createElement('Rozliczenie');
        $dom->appendChild($rozliczenie);

        if ( ! $this->obciazenia instanceof Optional) {
            foreach ($this->obciazenia as $obciazenia) {
                $obciazenia = $dom->importNode($obciazenia->toDom()->documentElement, true);

                $rozliczenie->appendChild($obciazenia);
            }
        }

        if ($this->sumaObciazen instanceof SumaObciazen) {
            $sumaObciazen = $dom->createElement('SumaObciazen');
            $sumaObciazen->appendChild($dom->createTextNode((string) $this->sumaObciazen));

            $rozliczenie->appendChild($sumaObciazen);
        }

        if ( ! $this->odliczenia instanceof Optional) {
            foreach ($this->odliczenia as $odliczenia) {
                $odliczenia = $dom->importNode($odliczenia->toDom()->documentElement, true);

                $rozliczenie->appendChild($odliczenia);
            }
        }

        if ($this->sumaOdliczen instanceof SumaOdliczen) {
            $sumaOdliczen = $dom->createElement('SumaOdliczen');
            $sumaOdliczen->appendChild($dom->createTextNode((string) $this->sumaOdliczen));

            $rozliczenie->appendChild($sumaOdliczen);
        }

        if ($this->rozliczenieGroup instanceof RozliczenieGroup) {
            /** @var DOMElement $rozliczenieGroup */
            $rozliczenieGroup = $this->rozliczenieGroup->toDom()->documentElement;

            foreach ($rozliczenieGroup->childNodes as $child) {
                $rozliczenieGroup->appendChild($dom->importNode($child, true));
            }
        }

        return $dom;
    }
}
