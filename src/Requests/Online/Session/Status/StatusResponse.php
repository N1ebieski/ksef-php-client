<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Session\Status;

use DateTimeImmutable;
use N1ebieski\KSEFClient\Requests\AbstractResponse;
use N1ebieski\KSEFClient\Requests\Online\DTOs\EntityType;
use N1ebieski\KSEFClient\Requests\Online\Session\DTOs\InvoiceStatus;
use N1ebieski\KSEFClient\Requests\Online\ValueObjects\ProcessingCode;
use N1ebieski\KSEFClient\Requests\Online\ValueObjects\ProcessingDescription;
use N1ebieski\KSEFClient\Requests\Online\ValueObjects\ReferenceNumber;

final readonly class StatusResponse extends AbstractResponse
{
    /**
     * @param array<int, InvoiceStatus>|null $invoiceStatusList
     */
    public function __construct(
        public DateTimeImmutable $timestamp,
        public ReferenceNumber $referenceNumber,
        public int $numberOfElements,
        public int $pageSize,
        public int $pageOffset,
        public ProcessingCode $processingCode,
        public ProcessingDescription $processingDescription,
        public DateTimeImmutable $creationTimestamp,
        public DateTimeImmutable $lastUpdateTimestamp,
        public EntityType $entityType,
        public ?array $invoiceStatusList = null,
    ) {
    }
}
