<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests;

use N1ebieski\KSEFClient\Resources\Online\ValueObjects\ElementReferenceNumber;
use N1ebieski\KSEFClient\Resources\Request;

final readonly class StatusRequest extends Request
{
    public function __construct(
        public ElementReferenceNumber $invoiceElementReferenceNumber
    ) {
    }
}
