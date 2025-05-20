<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Query\Invoice\DTOs;

use N1ebieski\KSEFClient\Requests\Online\Query\Invoice\ValueObjects\InvoicingDateFrom;
use N1ebieski\KSEFClient\Requests\Online\Query\Invoice\ValueObjects\InvoicingDateTo;
use N1ebieski\KSEFClient\Requests\Online\Query\Invoice\ValueObjects\QueryCriteriaInvoiceType;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class QueryCriteriaInvoiceRangeGroup extends AbstractDTO
{
    public QueryCriteriaInvoiceType $type;

    public function __construct(
        public InvoicingDateFrom $invoicingDateFrom,
        public InvoicingDateTo $invoicingDateTo,
    ) {
        $this->type = QueryCriteriaInvoiceType::Range;
    }
}
