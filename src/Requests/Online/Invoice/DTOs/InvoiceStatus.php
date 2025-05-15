<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DateTimeImmutable;
use N1ebieski\KSEFClient\Requests\Online\ValueObjects\InvoiceNumber;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\KsefReferenceNumber;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class InvoiceStatus extends AbstractDTO
{
    public function __construct(
        public ?InvoiceNumber $invoiceNumber = null,
        public ?KsefReferenceNumber $ksefReferenceNumber = null,
        public ?DateTimeImmutable $acquisitionTimestamp = null
    ) {
    }
}
