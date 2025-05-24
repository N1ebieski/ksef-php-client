<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Query\Invoice\Async\Status;

use N1ebieski\KSEFClient\Requests\AbstractRequest;
use N1ebieski\KSEFClient\Requests\Online\Query\Invoice\Async\ValueObjects\QueryElementReferenceNumber;

final readonly class StatusRequest extends AbstractRequest
{
    public function __construct(
        public QueryElementReferenceNumber $queryElementReferenceNumber,
    ) {
    }
}
