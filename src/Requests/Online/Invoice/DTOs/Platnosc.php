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
    /**
     * @param array<int, TerminPlatnosci> $terminPlatnosci
     * @param array<int, RachunekBankowy> $rachunekBankowy
     * @param array<int, RachunekBankowyFaktora> $rachunekBankowyFaktora
     */
    public function __construct(
        public Optional | ZaplaconoGroup $zaplaconoGroup = new Optional(),
        public array $terminPlatnosci = [],
        public Optional | FormaPlatnosciGroup | PlatnoscInnaGroup $platnoscGroup = new Optional(),
        public array $rachunekBankowy = [],
        public array $rachunekBankowyFaktora = [],
        public Optional | Skonto $skonto = new Optional()
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $platnosc = $dom->createElement('Platnosc');
        $dom->appendChild($platnosc);

        if ($this->zaplaconoGroup instanceof ZaplaconoGroup) {
            /** @var DOMElement $zaplaconoGroup */
            $zaplaconoGroup = $this->zaplaconoGroup->toDom()->documentElement;

            foreach ($zaplaconoGroup->childNodes as $child) {
                $platnosc->appendChild($dom->importNode($child, true));
            }
        }

        foreach ($this->terminPlatnosci as $terminPlatnosci) {
            $terminPlatnosci = $dom->importNode($terminPlatnosci->toDom()->documentElement, true);

            $platnosc->appendChild($terminPlatnosci);
        }

        if ( ! $this->platnoscGroup instanceof Optional) {
            /** @var DOMElement $platnoscGroup */
            $platnoscGroup = $this->platnoscGroup->toDom()->documentElement;

            foreach ($platnoscGroup->childNodes as $child) {
                $platnosc->appendChild($dom->importNode($child, true));
            }
        }

        foreach ($this->rachunekBankowy as $rachunekBankowy) {
            $rachunekBankowy = $dom->importNode($rachunekBankowy->toDom()->documentElement, true);

            $platnosc->appendChild($rachunekBankowy);
        }

        foreach ($this->rachunekBankowyFaktora as $rachunekBankowyFaktora) {
            $rachunekBankowyFaktora = $dom->importNode($rachunekBankowyFaktora->toDom()->documentElement, true);

            $platnosc->appendChild($rachunekBankowyFaktora);
        }

        if ($this->skonto instanceof Skonto) {
            $skonto = $dom->importNode($this->skonto->toDom()->documentElement, true);

            $platnosc->appendChild($skonto);
        }

        return $dom;
    }
}
