<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\BrakID;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\KodKraju;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\KodUE;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\Nazwa;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\NrID;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\NrVatUE;
use N1ebieski\KSEFClient\Support\DTO;
use N1ebieski\KSEFClient\ValueObjects\NIP;

final readonly class BrakIDGroup extends DTO implements DomSerializableInterface
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

        $brakIdGroup = $dom->createElement('BrakIDGroup');
        $dom->appendChild($brakIdGroup);

        $brakId = $dom->createElement('BrakID');
        $brakId->appendChild($dom->createTextNode((string) $this->brakId));

        $brakIdGroup->appendChild($brakId);

        return $dom;
    }
}
