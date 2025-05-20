<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Query\Invoice\DTOs;

use N1ebieski\KSEFClient\Contracts\BodyInterface;
use N1ebieski\KSEFClient\Requests\Online\Query\Invoice\ValueObjects\AcquisitionTimestampThresholdFrom;
use N1ebieski\KSEFClient\Requests\Online\Query\Invoice\ValueObjects\AcquisitionTimestampThresholdTo;
use N1ebieski\KSEFClient\Requests\Online\Query\Invoice\ValueObjects\QueryCriteriaInvoiceType;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Concerns\HasToBody;

final readonly class QueryCriteriaInvoiceIncrementalGroup extends AbstractDTO implements BodyInterface
{
    use HasToBody;

    public QueryCriteriaInvoiceType $type;

    public function __construct(
        public AcquisitionTimestampThresholdFrom $acquisitionTimestampThresholdFrom,
        public AcquisitionTimestampThresholdTo $acquisitionTimestampThresholdTo
    ) {
        $this->type = QueryCriteriaInvoiceType::Incremental;
    }
}
