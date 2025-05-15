<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\Status;

use DateTimeImmutable;
use N1ebieski\KSEFClient\Requests\AbstractResponse;
use N1ebieski\KSEFClient\Requests\Online\ValueObjects\ElementReferenceNumber;
use N1ebieski\KSEFClient\Requests\Online\ValueObjects\ProcessingCode;
use N1ebieski\KSEFClient\Requests\Online\ValueObjects\ProcessingDescription;
use N1ebieski\KSEFClient\Requests\Online\ValueObjects\ReferenceNumber;
use N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs\InvoiceStatus;

final readonly class StatusResponse extends AbstractResponse
{
    public function __construct(
        public DateTimeImmutable $timestamp,
        public ReferenceNumber $referenceNumber,
        public ProcessingCode $processingCode,
        public ProcessingDescription $processingDescription,
        public ElementReferenceNumber $elementReferenceNumber,
        public ?InvoiceStatus $invoiceStatus = null
    ) {
    }
}
