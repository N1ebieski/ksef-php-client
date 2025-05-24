<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use DOMElement;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\Validator\Rules\Array\MaxRule;
use N1ebieski\KSEFClient\Validator\Validator;

final readonly class Platnosc extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @var Optional|array<int, TerminPlatnosci>
     */
    public Optional | array $terminPlatnosci;

    /**
     * @var Optional|array<int, RachunekBankowy>
     */
    public Optional | array $rachunekBankowy;

    /**
     * @var Optional|array<int, RachunekBankowyFaktora>
     */
    public Optional | array $rachunekBankowyFaktora;

    /**
     * @param Optional|array<int, TerminPlatnosci> $terminPlatnosci
     * @param Optional|array<int, RachunekBankowy> $rachunekBankowy
     * @param Optional|array<int, RachunekBankowyFaktora> $rachunekBankowyFaktora
     */
    public function __construct(
        public Optional | ZaplataGroup | ZaplataCzesciowaGroup $zaplataGroup = new Optional(),
        Optional | array $terminPlatnosci = new Optional(),
        public Optional | FormaPlatnosciGroup | PlatnoscInnaGroup $platnoscGroup = new Optional(),
        Optional | array $rachunekBankowy = new Optional(),
        Optional | array $rachunekBankowyFaktora = new Optional(),
        public Optional | Skonto $skonto = new Optional()
    ) {
        Validator::validate([
            'terminPlatnosci' => $terminPlatnosci,
            'rachunekBankowy' => $rachunekBankowy,
            'rachunekBankowyFaktora' => $rachunekBankowyFaktora
        ], [
            'terminPlatnosci' => [new MaxRule(100)],
            'rachunekBankowy' => [new MaxRule(100)],
            'rachunekBankowyFaktora' => [new MaxRule(20)]
        ]);

        $this->terminPlatnosci = $terminPlatnosci;
        $this->rachunekBankowy = $rachunekBankowy;
        $this->rachunekBankowyFaktora = $rachunekBankowyFaktora;
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $platnosc = $dom->createElement('Platnosc');
        $dom->appendChild($platnosc);

        if ( ! $this->zaplataGroup instanceof Optional) {
            /** @var DOMElement $zaplataGroup */
            $zaplataGroup = $this->zaplataGroup->toDom()->documentElement;

            foreach ($zaplataGroup->childNodes as $child) {
                $platnosc->appendChild($dom->importNode($child, true));
            }
        }

        if ( ! $this->terminPlatnosci instanceof Optional) {
            foreach ($this->terminPlatnosci as $terminPlatnosci) {
                $terminPlatnosci = $dom->importNode($terminPlatnosci->toDom()->documentElement, true);

                $platnosc->appendChild($terminPlatnosci);
            }
        }

        if ( ! $this->platnoscGroup instanceof Optional) {
            /** @var DOMElement $platnoscGroup */
            $platnoscGroup = $this->platnoscGroup->toDom()->documentElement;

            foreach ($platnoscGroup->childNodes as $child) {
                $platnosc->appendChild($dom->importNode($child, true));
            }
        }

        if ( ! $this->rachunekBankowy instanceof Optional) {
            foreach ($this->rachunekBankowy as $rachunekBankowy) {
                $rachunekBankowy = $dom->importNode($rachunekBankowy->toDom()->documentElement, true);

                $platnosc->appendChild($rachunekBankowy);
            }
        }


        if ( ! $this->rachunekBankowyFaktora instanceof Optional) {
            foreach ($this->rachunekBankowyFaktora as $rachunekBankowyFaktora) {
                $rachunekBankowyFaktora = $dom->importNode($rachunekBankowyFaktora->toDom()->documentElement, true);

                $platnosc->appendChild($rachunekBankowyFaktora);
            }
        }

        if ($this->skonto instanceof Skonto) {
            $skonto = $dom->importNode($this->skonto->toDom()->documentElement, true);

            $platnosc->appendChild($skonto);
        }

        return $dom;
    }
}
