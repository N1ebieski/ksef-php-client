<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use DOMElement;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\Nazwa;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class Podmiot3DaneIdentyfikacyjne extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    public function __construct(
        public readonly NIPGroup | IDWewGroup | UEGroup | KrajGroup | BrakIDGroup $idGroup,
        public readonly Optional | Nazwa $nazwa = new Optional()
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $daneIdentyfikacyjne = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'DaneIdentyfikacyjne');
        $dom->appendChild($daneIdentyfikacyjne);

        /** @var DOMElement $idGroup */
        $idGroup = $this->idGroup->toDom()->documentElement;

        foreach ($idGroup->childNodes as $child) {
            $daneIdentyfikacyjne->appendChild($dom->importNode($child, true));
        }

        if ($this->nazwa instanceof Nazwa) {
            $nazwa = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Nazwa');
            $nazwa->appendChild($dom->createTextNode((string) $this->nazwa));

            $daneIdentyfikacyjne->appendChild($nazwa);
        }

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        $data['IdGroup'] = match (true) {
            isset($data['NIP']) => NIPGroup::normalizeXmlArray($data),
            isset($data['IDWew']) => IDWewGroup::normalizeXmlArray($data),
            isset($data['KodUE']) => UEGroup::normalizeXmlArray($data),
            isset($data['BrakID']) => BrakIDGroup::normalizeXmlArray($data),
            default => KrajGroup::normalizeXmlArray($data),
        };

        return Arr::onlyClassParameters($data, self::class);
    }
}
