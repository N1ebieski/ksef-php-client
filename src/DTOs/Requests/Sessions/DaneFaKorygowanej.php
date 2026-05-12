<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use DOMElement;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\XmlNormalizableInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\DataWystFaKorygowanej;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\NrFaKorygowanej;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class DaneFaKorygowanej extends AbstractDTO implements DomSerializableInterface, XmlNormalizableInterface
{
    /**
     * @param DataWystFaKorygowanej $dataWystFaKorygowanej Data wystawienia faktury korygowanej
     * @param NrFaKorygowanej $nrFaKorygowanej Numer faktury korygowanej
     */
    public function __construct(
        public readonly DataWystFaKorygowanej $dataWystFaKorygowanej,
        public readonly NrFaKorygowanej $nrFaKorygowanej,
        public readonly NrKSeFGroup | NrKSeFNGroup $nrKSeFGroup
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $daneFaKorygowanej = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'DaneFaKorygowanej');
        $dom->appendChild($daneFaKorygowanej);

        $dataWystFaKorygowanej = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'DataWystFaKorygowanej');
        $dataWystFaKorygowanej->appendChild($dom->createTextNode((string) $this->dataWystFaKorygowanej));

        $daneFaKorygowanej->appendChild($dataWystFaKorygowanej);

        $nrFaKorygowanej = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'NrFaKorygowanej');
        $nrFaKorygowanej->appendChild($dom->createTextNode((string) $this->nrFaKorygowanej));

        $daneFaKorygowanej->appendChild($nrFaKorygowanej);

        /** @var DOMElement $nrKSeFGroup */
        $nrKSeFGroup = $this->nrKSeFGroup->toDom()->documentElement;

        foreach ($nrKSeFGroup->childNodes as $child) {
            $daneFaKorygowanej->appendChild($dom->importNode($child, true));
        }

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        $data['NrKSeFGroup'] = match (true) {
            isset($data['NrKSeFFaKorygowanej']) => NrKSeFGroup::normalizeXmlArray($data),
            default => NrKSeFNGroup::normalizeXmlArray($data),
        };

        return Arr::only($data, ['DataWystFaKorygowanej', 'NrFaKorygowanej', 'NrKSeFGroup']);
    }
}
