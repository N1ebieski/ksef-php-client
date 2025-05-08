<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\Responses;

use DateTimeImmutable;
use N1ebieski\KSEFClient\Resources\Online\ValueObjects\ElementReferenceNumber;
use N1ebieski\KSEFClient\Resources\Online\ValueObjects\ProcessingCode;
use N1ebieski\KSEFClient\Resources\Online\ValueObjects\ProcessingDescription;
use N1ebieski\KSEFClient\Resources\Online\ValueObjects\ReferenceNumber;
use N1ebieski\KSEFClient\Resources\Response;

final readonly class SendResponse extends Response
{
    public function __construct(
        public DateTimeImmutable $timestamp,
        public ReferenceNumber $referenceNumber,
        public ProcessingCode $processingCode,
        public ProcessingDescription $processingDescription,
        public ElementReferenceNumber $elementReferenceNumber
    ) {
    }
}
