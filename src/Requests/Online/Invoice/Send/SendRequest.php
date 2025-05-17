<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\Send;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\XmlSerializableInterface;
use N1ebieski\KSEFClient\Requests\AbstractRequest;
use N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs\Fa;
use N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs\Naglowek;
use N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs\Podmiot1;
use N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs\Podmiot2;
use N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs\Stopka;
use N1ebieski\KSEFClient\Requests\Online\ValueObjects\XmlNamespace;
use N1ebieski\KSEFClient\Support\Concerns\HasToXml;

final readonly class SendRequest extends AbstractRequest implements XmlSerializableInterface, DomSerializableInterface
{
    use HasToXml;

    /**
     * @param Podmiot1 $podmiot1 Dane podatnika. Imię i nazwisko lub nazwa sprzedawcy towarów lub usług
     * @param Podmiot2 $podmiot2 Dane nabywcy
     * @param Fa $fa Na podstawie art. 106a - 106q ustawy. Pola dotyczące wartości sprzedaży i podatku wypełnia się w walucie, w której wystawiono fakturę, z wyjątkiem pól dotyczących podatku przeliczonego zgodnie z przepisami Działu VI w związku z art. 106e ust. 11 ustawy. W przypadku wystawienia faktury korygującej, wypełnia się wszystkie pola wg stanu po korekcie, a pola dotyczące podstaw opodatkowania, podatku oraz należności ogółem wypełnia się poprzez różnicę
     * @param null|Stopka $stopka Pozostałe dane na fakturze
     * @return void
     */
    public function __construct(
        public Naglowek $naglowek,
        public Podmiot1 $podmiot1,
        public Podmiot2 $podmiot2,
        public Fa $fa,
        public ?Stopka $stopka = null
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $faktura = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'Faktura');
        $faktura->setAttribute('xmlns:xsi', (string) XmlNamespace::Xsi->value);

        $dom->appendChild($faktura);

        $naglowek = $dom->importNode($this->naglowek->toDom()->documentElement, true);

        $faktura->appendChild($naglowek);

        $podmiot1 = $dom->importNode($this->podmiot1->toDom()->documentElement, true);

        $faktura->appendChild($podmiot1);

        $podmiot2 = $dom->importNode($this->podmiot2->toDom()->documentElement, true);

        $faktura->appendChild($podmiot2);

        $fa = $dom->importNode($this->fa->toDom()->documentElement, true);

        $faktura->appendChild($fa);

        if ($this->stopka instanceof Stopka) {
            $stopka = $dom->importNode($this->stopka->toDom()->documentElement, true);

            $faktura->appendChild($stopka);
        }

        return $dom;
    }
}
