<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\Get;

use N1ebieski\KSEFClient\Requests\AbstractRequest;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\KsefReferenceNumber;

final readonly class GetRequest extends AbstractRequest
{
    public function __construct(
        public KsefReferenceNumber $ksefReferenceNumber
    ) {
    }
}
