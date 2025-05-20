<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Session\Status;

use N1ebieski\KSEFClient\Requests\AbstractRequest;
use N1ebieski\KSEFClient\Requests\ValueObjects\ReferenceNumber;
use N1ebieski\KSEFClient\Support\Optional;

final readonly class StatusRequest extends AbstractRequest
{
    public function __construct(
        public Optional | ReferenceNumber $referenceNumber = new Optional(),
        public Optional | int $pageSize = new Optional(),
        public Optional | int $pageOffset = new Optional(),
        public Optional | bool $includeDetails = new Optional()
    ) {
    }
}
