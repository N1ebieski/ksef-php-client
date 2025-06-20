<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Query\Invoice\Async\Fetch;

use N1ebieski\KSEFClient\Requests\AbstractRequest;
use N1ebieski\KSEFClient\Requests\Online\Query\Invoice\Async\ValueObjects\PartElementReferenceNumber;
use N1ebieski\KSEFClient\Requests\Online\Query\Invoice\Async\ValueObjects\QueryElementReferenceNumber;

final readonly class FetchRequest extends AbstractRequest
{
    public function __construct(
        public QueryElementReferenceNumber $queryElementReferenceNumber,
        public PartElementReferenceNumber $partElementReferenceNumber
    ) {
    }
}
