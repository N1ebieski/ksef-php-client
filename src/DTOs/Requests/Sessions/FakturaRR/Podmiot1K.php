<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions\FakturaRR;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\XmlNormalizableInterface;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\FakturaRR\Adres;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\FakturaRR\Podmiot1KDaneIdentyfikacyjne;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class Podmiot1K extends AbstractDTO implements DomSerializableInterface, XmlNormalizableInterface
{
    /**
     * @param Podmiot1KDaneIdentyfikacyjne $daneIdentyfikacyjne Dane identyfikujące podatnika
     * @param Adres $adres Adres podatnika
     */
    public function __construct(
        public readonly Podmiot1KDaneIdentyfikacyjne $daneIdentyfikacyjne,
        public readonly Adres $adres,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $podmiot1K = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'Podmiot1K');
        $dom->appendChild($podmiot1K);

        $daneIdentyfikacyjne = $dom->importNode($this->daneIdentyfikacyjne->toDom()->documentElement, true);

        $podmiot1K->appendChild($daneIdentyfikacyjne);

        $adres = $dom->importNode($this->adres->toDom()->documentElement, true);

        $podmiot1K->appendChild($adres);

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        $data['DaneIdentyfikacyjne'] = Podmiot1KDaneIdentyfikacyjne::normalizeXmlArray($data['DaneIdentyfikacyjne']);

        $data['Adres'] = Adres::normalizeXmlArray($data['Adres']);

        return Arr::only($data, ['DaneIdentyfikacyjne', 'Adres']);
    }
}
