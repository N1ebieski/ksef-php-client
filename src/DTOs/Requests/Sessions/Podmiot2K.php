<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\XmlNormalizableInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\IDNabywcy;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class Podmiot2K extends AbstractDTO implements DomSerializableInterface, XmlNormalizableInterface
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

    public static function normalizeXmlArray(array $data): array
    {
        $data['DaneIdentyfikacyjne'] = Podmiot2KDaneIdentyfikacyjne::normalizeXmlArray($data['DaneIdentyfikacyjne']);

        $data['Adres'] = match (true) {
            isset($data['Adres']) => Adres::normalizeXmlArray($data['Adres']),
            default => new Optional(),
        };

        $data['idNabywcy'] = $data['IDNabywcy'] ?? new Optional();

        return Arr::only($data, ['DaneIdentyfikacyjne', 'Adres', 'idNabywcy']);
    }
}
