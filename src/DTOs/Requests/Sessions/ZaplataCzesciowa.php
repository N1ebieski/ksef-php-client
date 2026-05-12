<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use DOMElement;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\XmlNormalizableInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\DataZaplatyCzesciowej;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\KwotaZaplatyCzesciowej;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class ZaplataCzesciowa extends AbstractDTO implements DomSerializableInterface, XmlNormalizableInterface
{
    /**
     * @param DataZaplatyCzesciowej $dataZaplatyCzesciowej Data zapłaty częściowej, jeśli do wystawienia faktury płatność częściowa została dokonana
     */
    public function __construct(
        public readonly KwotaZaplatyCzesciowej $kwotaZaplatyCzesciowej,
        public readonly DataZaplatyCzesciowej $dataZaplatyCzesciowej,
        public readonly Optional | FormaPlatnosciGroup | PlatnoscInnaGroup $platnoscGroup = new Optional(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $zaplataCzesciowa = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'ZaplataCzesciowa');
        $dom->appendChild($zaplataCzesciowa);

        $kwotaZaplatyCzesciowej = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'KwotaZaplatyCzesciowej');
        $kwotaZaplatyCzesciowej->appendChild($dom->createTextNode((string) $this->kwotaZaplatyCzesciowej));

        $zaplataCzesciowa->appendChild($kwotaZaplatyCzesciowej);

        $dataZaplatyCzesciowej = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'DataZaplatyCzesciowej');
        $dataZaplatyCzesciowej->appendChild($dom->createTextNode((string) $this->dataZaplatyCzesciowej));

        $zaplataCzesciowa->appendChild($dataZaplatyCzesciowej);

        if ( ! $this->platnoscGroup instanceof Optional) {
            /** @var DOMElement $platnoscGroup */
            $platnoscGroup = $this->platnoscGroup->toDom()->documentElement;

            foreach ($platnoscGroup->childNodes as $child) {
                $zaplataCzesciowa->appendChild($dom->importNode($child, true));
            }
        }

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        $data['PlatnoscGroup'] = match (true) {
            isset($data['FormaPlatnosci']) => FormaPlatnosciGroup::normalizeXmlArray($data),
            isset($data['PlatnoscInna']) => PlatnoscInnaGroup::normalizeXmlArray($data),
            default => new Optional(),
        };

        return Arr::only($data, ['KwotaZaplatyCzesciowej', 'DataZaplatyCzesciowej', 'PlatnoscGroup']);
    }
}
