<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use DOMElement;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class Zwolnienie extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    public function __construct(
        public readonly P_19Group | P_19NGroup $p_19Group = new P_19NGroup(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $zwolnienie = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Zwolnienie');
        $dom->appendChild($zwolnienie);

        /** @var DOMElement $p_19Group */
        $p_19Group = $this->p_19Group->toDom()->documentElement;

        foreach ($p_19Group->childNodes as $child) {
            $zwolnienie->appendChild($dom->importNode($child, true));
        }

        $dom->appendChild($zwolnienie);

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        $data['P_19Group'] = match (true) {
            isset($data['P_19']) => P_19Group::normalizeXmlArray($data),
            default => P_19NGroup::normalizeXmlArray($data),
        };

        return Arr::onlyClassParameters($data, self::class);
    }
}
