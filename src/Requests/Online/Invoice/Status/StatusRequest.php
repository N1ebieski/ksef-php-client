<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\Status;

use N1ebieski\KSEFClient\Requests\AbstractRequest;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\InvoiceElementReferenceNumber;

final readonly class StatusRequest extends AbstractRequest
{
    public function __construct(
        public InvoiceElementReferenceNumber $invoiceElementReferenceNumber
    ) {
    }
}
