<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\IDNabywcy;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;

final readonly class Podmiot2K extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param Podmiot2KDaneIdentyfikacyjne $daneIdentyfikacyjne Dane identyfikujące nabywcę
     * @param Adres|Optional $adres Adres nabywcy
     * @param IDNabywcy|Optional $idNabywcy Unikalny klucz powiązania danych nabywcy na fakturach korygujących, w przypadku gdy dane nabywcy na fakturze korygującej zmieniły się w stosunku do danych na fakturze korygowanej
     * @return void
     */
    public function __construct(
        public Podmiot2KDaneIdentyfikacyjne $daneIdentyfikacyjne,
        public Optional | Adres $adres = new Optional(),
        public Optional | IDNabywcy $idNabywcy = new Optional()
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $podmiot2 = $dom->createElement('Podmiot2K');
        $dom->appendChild($podmiot2);

        $daneIdentyfikacyjne = $dom->importNode($this->daneIdentyfikacyjne->toDom()->documentElement, true);

        $podmiot2->appendChild($daneIdentyfikacyjne);

        if ($this->adres instanceof Adres) {
            $adres = $dom->importNode($this->adres->toDom()->documentElement, true);

            $podmiot2->appendChild($adres);
        }

        if ($this->idNabywcy instanceof IDNabywcy) {
            $idNabywcy = $dom->createElement('IDNabywcy');
            $idNabywcy->appendChild($dom->createTextNode((string) $this->idNabywcy));
            $podmiot2->appendChild($idNabywcy);
        }

        return $dom;
    }
}
