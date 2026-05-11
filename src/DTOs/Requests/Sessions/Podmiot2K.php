<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\IDNabywcy;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;

final class Podmiot2K extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    /**
     * @param Podmiot2KDaneIdentyfikacyjne $daneIdentyfikacyjne Dane identyfikujące nabywcę
     * @param Adres|Optional $adres Adres nabywcy
     * @param IDNabywcy|Optional $idNabywcy Unikalny klucz powiązania danych nabywcy na fakturach korygujących, w przypadku gdy dane nabywcy na fakturze korygującej zmieniły się w stosunku do danych na fakturze korygowanej
     */
    public function __construct(
        public readonly Podmiot2KDaneIdentyfikacyjne $daneIdentyfikacyjne,
        public readonly Optional | Adres $adres = new Optional(),
        public readonly Optional | IDNabywcy $idNabywcy = new Optional()
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $podmiot2 = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Podmiot2K');
        $dom->appendChild($podmiot2);

        $daneIdentyfikacyjne = $dom->importNode($this->daneIdentyfikacyjne->toDom()->documentElement, true);

        $podmiot2->appendChild($daneIdentyfikacyjne);

        if ($this->adres instanceof Adres) {
            $adres = $dom->importNode($this->adres->toDom()->documentElement, true);

            $podmiot2->appendChild($adres);
        }

        if ($this->idNabywcy instanceof IDNabywcy) {
            $idNabywcy = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'IDNabywcy');
            $idNabywcy->appendChild($dom->createTextNode((string) $this->idNabywcy));
            $podmiot2->appendChild($idNabywcy);
        }

        return $dom;
    }

    public static function fromXmlArray(array $data): self
    {
        return new self(
            daneIdentyfikacyjne: Podmiot2KDaneIdentyfikacyjne::fromXmlArray($data['DaneIdentyfikacyjne']),
            adres: isset($data['Adres']) ? Adres::fromXmlArray($data['Adres']) : new Optional(),
            idNabywcy: isset($data['IDNabywcy']) ? new IDNabywcy($data['IDNabywcy']) : new Optional(),
        );
    }
}
