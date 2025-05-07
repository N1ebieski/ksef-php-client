<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\Responses;

use DateTimeImmutable;
use N1ebieski\KSEFClient\Resources\Response;

final readonly class SendResponse extends Response
{
    public function __construct(
        public DateTimeImmutable $timestamp,
        public string $referenceNumber,
        public int $processingCode,
        public string $processingDescription,
        public string $elementReferenceNumber
    ) {
    }
}
