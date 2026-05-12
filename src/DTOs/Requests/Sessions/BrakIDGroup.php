<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\XmlNormalizableInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\BrakID;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class BrakIDGroup extends AbstractDTO implements DomSerializableInterface, XmlNormalizableInterface
{
    /**
     * @param BrakID $brakID Podmiot nie posiada identyfikatora podatkowego lub identyfikator nie występuje na fakturze: 1- tak
     */
    public function __construct(
        public readonly BrakID $brakID,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $brakIDGroup = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'BrakIDGroup');
        $dom->appendChild($brakIDGroup);

        $brakID = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'BrakID');
        $brakID->appendChild($dom->createTextNode((string) $this->brakID->value));

        $brakIDGroup->appendChild($brakID);

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        return Arr::only($data, ['BrakID']);
    }
}
