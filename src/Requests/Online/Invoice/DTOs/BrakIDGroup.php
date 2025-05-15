<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\BrakID;
use N1ebieski\KSEFClient\Requests\Online\ValueObjects\XmlNamespace;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class BrakIDGroup extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param BrakID $brakId Podmiot nie posiada identyfikatora podatkowego lub identyfikator nie występuje na fakturze: 1- tak
     * @return void
     */
    public function __construct(
        public BrakID $brakId,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $brakIdGroup = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'BrakIDGroup');
        $dom->appendChild($brakIdGroup);

        $brakId = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'BrakID');
        $brakId->appendChild($dom->createTextNode((string) $this->brakId->value));

        $brakIdGroup->appendChild($brakId);

        return $dom;
    }
}
