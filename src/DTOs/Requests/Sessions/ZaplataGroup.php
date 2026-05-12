<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\XmlNormalizableInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\DataZaplaty;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\Zaplacono;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class ZaplataGroup extends AbstractDTO implements DomSerializableInterface, XmlNormalizableInterface
{
    /**
     * @param Zaplacono $zaplacono Znacznik informujący, że kwota należności wynikająca z faktury została zapłacona: 1 - zapłacono
     * @param DataZaplaty $dataZaplaty Data zapłaty, jeśli do wystawienia faktury płatność została dokonana
     */
    public function __construct(
        public readonly DataZaplaty $dataZaplaty,
        public readonly Zaplacono $zaplacono = Zaplacono::Default,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $zaplataGroup = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'ZaplataGroup');
        $dom->appendChild($zaplataGroup);

        $zaplacono = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Zaplacono');
        $zaplacono->appendChild($dom->createTextNode((string) $this->zaplacono->value));

        $zaplataGroup->appendChild($zaplacono);

        $dataZaplaty = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'DataZaplaty');
        $dataZaplaty->appendChild($dom->createTextNode((string) $this->dataZaplaty));

        $zaplataGroup->appendChild($dataZaplaty);

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        return Arr::only($data, ['Zaplacono', 'DataZaplaty']);
    }
}
