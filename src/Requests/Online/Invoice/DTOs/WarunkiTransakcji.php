<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use DOMElement;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\NrPartiiTowaru;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\PodmiotPosredniczacy;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\WarunkiDostawy;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\Validator\Rules\Array\MaxRule;
use N1ebieski\KSEFClient\Validator\Validator;

final readonly class WarunkiTransakcji extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @var Optional|array<int, Umowy>
     */
    public Optional | array $umowy;

    /**
     * @var Optional|array<int, Zamowienia>
     */
    public Optional | array $zamowienia;

    /**
     * @var Optional|array<int, NrPartiiTowaru>
     */
    public Optional | array $nrPartiiTowaru;

    /**
     * @var Optional|array<int, Transport>
     */
    public Optional | array $transport;

    /**
     * @param Optional|array<int, Umowy> $umowy
     * @param Optional|array<int, Zamowienia> $zamowienia
     * @param Optional|array<int, NrPartiiTowaru> $nrPartiiTowaru
     * @param Optional|WarunkiDostawy $warunkiDostawy Warunki dostawy towarów - w przypadku istnienia pomiędzy stronami transakcji, umowy określającej warunki dostawy tzw. Incoterms
     * @param Optional|array<int, Transport> $transport
     * @param Optional|PodmiotPosredniczacy $podmiotPosredniczacy Wartość "1" oznacza dostawę dokonaną przez podmiot, o którym mowa w art. 22 ust. 2d ustawy. Pole dotyczy przypadku, w którym podmiot uczestniczy w transakcji łańcuchowej innej niż procedura trójstronna uproszczona, o której mowa w art. 135 ust. 1 pkt 4 ustawy
     * @return void
     */
    public function __construct(
        Optional | array $umowy = new Optional(),
        Optional | array $zamowienia = new Optional(),
        Optional | array $nrPartiiTowaru = new Optional(),
        public Optional | WarunkiDostawy $warunkiDostawy = new Optional(),
        public Optional | WalutaUmownaGroup $walutaUmownaGroup = new Optional(),
        Optional | array $transport = new Optional(),
        public Optional | PodmiotPosredniczacy $podmiotPosredniczacy = new Optional()
    ) {
        Validator::validate([
            'umowy' => $umowy,
            'zamowienia' => $zamowienia,
            'nrPartiiTowaru' => $nrPartiiTowaru,
            'transport' => $transport,
        ], [
            'umowy' => [new MaxRule(100)],
            'zamowienia' => [new MaxRule(100)],
            'nrPartiiTowaru' => [new MaxRule(1000)],
            'transport' => [new MaxRule(20)]
        ]);

        $this->umowy = $umowy;
        $this->zamowienia = $zamowienia;
        $this->nrPartiiTowaru = $nrPartiiTowaru;
        $this->transport = $transport;
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $warunkiTransakcji = $dom->createElement('WarunkiTransakcji');
        $dom->appendChild($warunkiTransakcji);

        if ( ! $this->umowy instanceof Optional) {
            foreach ($this->umowy as $umowa) {
                $umowa = $dom->importNode($umowa->toDom()->documentElement, true);
                $warunkiTransakcji->appendChild($umowa);
            }
        }

        if ( ! $this->zamowienia instanceof Optional) {
            foreach ($this->zamowienia as $zamowienie) {
                $zamowienie = $dom->importNode($zamowienie->toDom()->documentElement, true);
                $warunkiTransakcji->appendChild($zamowienie);
            }
        }

        if ( ! $this->nrPartiiTowaru instanceof Optional) {
            foreach ($this->nrPartiiTowaru as $nrPartiiTowaru) {
                $_nrPartiiTowaru = $dom->createElement('NrPartiiTowaru');
                $_nrPartiiTowaru->appendChild($dom->createTextNode((string) $nrPartiiTowaru));

                $warunkiTransakcji->appendChild($_nrPartiiTowaru);
            }
        }

        if ($this->warunkiDostawy instanceof WarunkiDostawy) {
            $warunkiDostawy = $dom->createElement('WarunkiDostawy');
            $warunkiDostawy->appendChild($dom->createTextNode((string) $this->warunkiDostawy));

            $warunkiTransakcji->appendChild($warunkiDostawy);
        }

        if ($this->walutaUmownaGroup instanceof WalutaUmownaGroup) {
            /** @var DOMElement $walutaUmownaGroup */
            $walutaUmownaGroup = $dom->importNode($this->walutaUmownaGroup->toDom()->documentElement, true);

            foreach ($walutaUmownaGroup->childNodes as $child) {
                $warunkiTransakcji->appendChild($dom->importNode($child, true));
            }
        }

        if ( ! $this->transport instanceof Optional) {
            foreach ($this->transport as $transport) {
                $transport = $dom->importNode($transport->toDom()->documentElement, true);

                $warunkiTransakcji->appendChild($transport);
            }
        }

        if ($this->podmiotPosredniczacy instanceof PodmiotPosredniczacy) {
            $podmiotPosredniczacy = $dom->createElement('PodmiotPosredniczacy');
            $podmiotPosredniczacy->appendChild($dom->createTextNode((string) $this->podmiotPosredniczacy->value));

            $warunkiTransakcji->appendChild($podmiotPosredniczacy);
        }

        return $dom;
    }
}
