<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Session\DTOs;

use DateTimeImmutable;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\KsefReferenceNumber;
use N1ebieski\KSEFClient\Requests\Online\ValueObjects\ElementReferenceNumber;
use N1ebieski\KSEFClient\Requests\Online\ValueObjects\InvoiceNumber;
use N1ebieski\KSEFClient\Requests\ValueObjects\ProcessingCode;
use N1ebieski\KSEFClient\Requests\ValueObjects\ProcessingDescription;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class InvoiceStatus extends AbstractDTO
{
    public function __construct(
        public ProcessingCode $processingCode,
        public ProcessingDescription $processingDescription,
        public ElementReferenceNumber $elementReferenceNumber,
        public InvoiceNumber $invoiceNumber,
        public KsefReferenceNumber $ksefReferenceNumber,
        public DateTimeImmutable $acquisitionTimestamp,
    ) {
    }
}
