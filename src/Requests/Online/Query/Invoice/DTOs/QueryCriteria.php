<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Query\Invoice\DTOs;

use DateTimeInterface;
use N1ebieski\KSEFClient\Requests\Online\Query\Invoice\ValueObjects\SubjectType;
use N1ebieski\KSEFClient\Requests\Online\Query\Invoice\ValueObjects\Type;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;

final readonly class QueryCriteria extends AbstractDTO
{
    public function __construct(
        public SubjectType $subjectType,
        public Type $type,
        public QueryCriteriaInvoiceRangeTypeGroup $typeCriteriagroup,
        public Optional | DateTimeInterface $hidingDateFrom = new Optional(),
        public Optional | DateTimeInterface $hidingDateTo = new Optional(),
        public Optional | bool $isHidden = new Optional()
    ) {
    }
}
