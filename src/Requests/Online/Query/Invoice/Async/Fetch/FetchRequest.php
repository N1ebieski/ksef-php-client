<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Query\Invoice\Async\Fetch;

use N1ebieski\KSEFClient\Requests\AbstractRequest;
use N1ebieski\KSEFClient\Requests\Online\Query\Invoice\Async\ValueObjects\PartElementReferenceNumber;
use N1ebieski\KSEFClient\Requests\Online\ValueObjects\ElementReferenceNumber;

final readonly class FetchRequest extends AbstractRequest
{
    public function __construct(
        public ElementReferenceNumber $queryElementReferenceNumber,
        public PartElementReferenceNumber $partElementReferenceNumber
    ) {
    }
}
