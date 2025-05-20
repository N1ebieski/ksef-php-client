<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Query\Invoice\DTOs;

use N1ebieski\KSEFClient\Requests\Online\Query\Invoice\ValueObjects\AcquisitionTimestampThresholdFrom;
use N1ebieski\KSEFClient\Requests\Online\Query\Invoice\ValueObjects\AcquisitionTimestampThresholdTo;
use N1ebieski\KSEFClient\Requests\Online\Query\Invoice\ValueObjects\QueryCriteriaInvoiceType;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class QueryCriteriaInvoiceIncrementalGroup extends AbstractDTO
{
    public QueryCriteriaInvoiceType $type;

    public function __construct(
        public AcquisitionTimestampThresholdFrom $acquisitionTimestampThresholdFrom,
        public AcquisitionTimestampThresholdTo $acquisitionTimestampThresholdTo
    ) {
        $this->type = QueryCriteriaInvoiceType::Incremental;
    }
}
