<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\NrKlienta;
use N1ebieski\KSEFClient\Support\DTO;

final readonly class Podmiot2 extends DTO implements DomSerializableInterface
{
    /**
     * @param Podmiot2DaneIdentyfikacyjne $daneIdentyfikacyjne Dane identyfikujące nabywcę
     * @param Adres|null $adres Adres nabywcy
     * @param array<int, DaneKontaktowe> $daneKontaktowe Dane kontaktowe nabywcy
     * @param null|NrKlienta $nrKlienta Numer klienta dla przypadków, w których nabywca posługuje się nim w umowie lub zamówieniu
     * @return void
     */
    public function __construct(
        public Podmiot2DaneIdentyfikacyjne $daneIdentyfikacyjne,
        public ?Adres $adres = null,
        public array $daneKontaktowe = [],
        public ?NrKlienta $nrKlienta = null
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $podmiot2 = $dom->createElement('Podmiot2');
        $dom->appendChild($podmiot2);

        $daneIdentyfikacyjne = $dom->importNode($this->daneIdentyfikacyjne->toDom()->documentElement, true);

        $podmiot2->appendChild($daneIdentyfikacyjne);

        if ($this->adres !== null) {
            $adres = $dom->importNode($this->adres->toDom()->documentElement, true);

            $podmiot2->appendChild($adres);
        }

        foreach ($this->daneKontaktowe as $daneKontaktowe) {
            $daneKontaktowe = $dom->importNode($daneKontaktowe->toDom()->documentElement, true);
            $podmiot2->appendChild($daneKontaktowe);
        }

        if ($this->nrKlienta !== null) {
            $nrKlienta = $dom->createElement('NrKlienta');
            $nrKlienta->appendChild($dom->createTextNode((string) $this->nrKlienta));
            $podmiot2->appendChild($nrKlienta);
        }

        return $dom;
    }
}
