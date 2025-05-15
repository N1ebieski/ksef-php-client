<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Session\Status;

use N1ebieski\KSEFClient\Requests\AbstractRequest;
use N1ebieski\KSEFClient\Requests\Online\ValueObjects\ReferenceNumber;

final readonly class StatusRequest extends AbstractRequest
{
    public function __construct(
        public ?ReferenceNumber $referenceNumber = null,
        public ?int $pageSize = null,
        public ?int $pageOffset = null,
        public ?bool $includeDetails = null
    ) {
    }
}
