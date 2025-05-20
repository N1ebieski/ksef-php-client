<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Query\Invoice\DTOs;

use DateTimeImmutable;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class QueryCriteriaInvoiceRangeTypeGroup extends AbstractDTO
{
    public function __construct(
        public \DateTimeInterface $invoicingDateFrom,
        public \DateTimeInterface $invoicingDateTo,
    ) {
    }
}
