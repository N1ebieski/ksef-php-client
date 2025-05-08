<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\Responses\DTOs;

use DateTimeImmutable;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\Responses\ValueObjects\InvoiceNumber;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\Responses\ValueObjects\KsefReferenceNumber;
use N1ebieski\KSEFClient\Support\DTO;

final readonly class InvoiceStatus extends DTO
{
    public function __construct(
        public ?InvoiceNumber $invoiceNumber = null,
        public ?KsefReferenceNumber $ksefReferenceNumber = null,
        public ?DateTimeImmutable $acquisitionTimestamp = null
    ) {
    }
}
