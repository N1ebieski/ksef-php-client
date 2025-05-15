<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\ValueObjects\XmlNamespace;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class Podmiot1 extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param Podmiot1DaneIdentyfikacyjne $daneIdentyfikacyjne Dane identyfikujące podatnika
     * @param Adres $adres Adres podatnika
     * @param array<int, DaneKontaktowe> $daneKontaktowe Dane kontaktowe podatnika
     * @return void
     */
    public function __construct(
        public Podmiot1DaneIdentyfikacyjne $daneIdentyfikacyjne,
        public Adres $adres,
        public array $daneKontaktowe = []
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $podmiot1 = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'Podmiot1');
        $dom->appendChild($podmiot1);

        $daneIdentyfikacyjne = $dom->importNode($this->daneIdentyfikacyjne->toDom()->documentElement, true);

        $podmiot1->appendChild($daneIdentyfikacyjne);

        $adres = $dom->importNode($this->adres->toDom()->documentElement, true);

        $podmiot1->appendChild($adres);

        foreach ($this->daneKontaktowe as $daneKontaktowe) {
            $daneKontaktowe = $dom->importNode($daneKontaktowe->toDom()->documentElement, true);
            $podmiot1->appendChild($daneKontaktowe);
        }

        return $dom;
    }
}
