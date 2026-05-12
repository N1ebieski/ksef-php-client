<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use DOMElement;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\XmlNormalizableInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class NoweSrodkiTransportu extends AbstractDTO implements DomSerializableInterface, XmlNormalizableInterface
{
    public function __construct(
        public readonly P_22Group | P_22NGroup $p_22Group = new P_22NGroup(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $noweSrodkiTransportu = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'NoweSrodkiTransportu');
        $dom->appendChild($noweSrodkiTransportu);

        /** @var DOMElement $p_22Group */
        $p_22Group = $this->p_22Group->toDom()->documentElement;

        foreach ($p_22Group->childNodes as $child) {
            $noweSrodkiTransportu->appendChild($dom->importNode($child, true));
        }

        $dom->appendChild($noweSrodkiTransportu);

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        $data['P_22Group'] = match (true) {
            isset($data['P_22']) => P_22Group::normalizeXmlArray($data),
            default => P_22NGroup::normalizeXmlArray($data),
        };

        return Arr::only($data, ['P_22Group']);
    }
}
