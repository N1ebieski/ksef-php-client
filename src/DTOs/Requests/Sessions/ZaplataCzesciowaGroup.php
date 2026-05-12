<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\XmlNormalizableInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\Validator\Rules\Array\MaxRule;
use N1ebieski\KSEFClient\Validator\Rules\Array\MinRule;
use N1ebieski\KSEFClient\Validator\Validator;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\ZnacznikZaplatyCzesciowej;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class ZaplataCzesciowaGroup extends AbstractDTO implements DomSerializableInterface, XmlNormalizableInterface
{
    /**
     * @var Optional|array<int, ZaplataCzesciowa>
     */
    public readonly Optional | array $zaplataCzesciowa;

    /**
     * @param Optional|array<int, ZaplataCzesciowa> $zaplataCzesciowa Dane zapłat częściowych
     * @param ZnacznikZaplatyCzesciowej $znacznikZaplatyCzesciowej Znacznik informujący, że należność wynikająca z faktury została zapłacona w części lub w całości: 1 - zapłacono w części; 2 - zapłacono w całości, jeśli należność wynikająca z faktury została zapłacona w dwóch lub więcej częściach, a ostatnia płatność jest płatnością końcową
     */
    public function __construct(
        Optional | array $zaplataCzesciowa = new Optional(),
        public readonly ZnacznikZaplatyCzesciowej $znacznikZaplatyCzesciowej = ZnacznikZaplatyCzesciowej::Default
    ) {
        Validator::validate([
            'zaplataCzesciowa' => $zaplataCzesciowa,
        ], [
            'zaplataCzesciowa' => [new MinRule(1), new MaxRule(100)],
        ]);

        $this->zaplataCzesciowa = $zaplataCzesciowa;
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $zaplataCzesciowaGroup = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'ZaplataCzesciowaGroup');
        $dom->appendChild($zaplataCzesciowaGroup);

        $znacznikZaplatyCzesciowej = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'ZnacznikZaplatyCzesciowej');
        $znacznikZaplatyCzesciowej->appendChild($dom->createTextNode((string) $this->znacznikZaplatyCzesciowej->value));

        $zaplataCzesciowaGroup->appendChild($znacznikZaplatyCzesciowej);

        if ( ! $this->zaplataCzesciowa instanceof Optional) {
            foreach ($this->zaplataCzesciowa as $zaplataCzesciowa) {
                $zaplataCzesciowa = $dom->importNode($zaplataCzesciowa->toDom()->documentElement, true);

                $zaplataCzesciowaGroup->appendChild($zaplataCzesciowa);
            }
        }

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        $data['ZaplataCzesciowa'] = match (true) {
            isset($data['ZaplataCzesciowa']) => array_map(
                fn (array $item): array => ZaplataCzesciowa::normalizeXmlArray($item),
                Arr::ensureList($data['ZaplataCzesciowa'])
            ),
            default => new Optional(),
        };

        return Arr::only($data, ['ZaplataCzesciowa', 'ZnacznikZaplatyCzesciowej']);
    }
}
