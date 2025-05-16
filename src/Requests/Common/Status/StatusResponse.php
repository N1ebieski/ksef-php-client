<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Common\Status;

use DateTimeImmutable;
use N1ebieski\KSEFClient\Requests\AbstractResponse;
use N1ebieski\KSEFClient\Requests\Common\ValueObjects\Upo;
use N1ebieski\KSEFClient\Requests\Online\ValueObjects\ProcessingCode;
use N1ebieski\KSEFClient\Requests\Online\ValueObjects\ProcessingDescription;
use N1ebieski\KSEFClient\Requests\Online\ValueObjects\ReferenceNumber;

final readonly class StatusResponse extends AbstractResponse
{
    public function __construct(
        public ProcessingCode $processingCode,
        public ProcessingDescription $processingDescription,
        public ReferenceNumber $referenceNumber,
        public DateTimeImmutable $timestamp,
        public ?Upo $upo = null
    ) {
    }
}
