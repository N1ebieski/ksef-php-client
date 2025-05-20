<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Query\Invoice\DTOs;

use DateTimeInterface;
use N1ebieski\KSEFClient\Contracts\BodyInterface;
use N1ebieski\KSEFClient\Requests\Online\Query\Invoice\ValueObjects\SubjectType;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\Support\ValueObjects\KeyType;

final readonly class QueryCriteria extends AbstractDTO implements BodyInterface
{
    public function __construct(
        public SubjectType $subjectType,
        public QueryCriteriaInvoiceRangeGroup | QueryCriteriaInvoiceIncrementalGroup | QueryCriteriaInvoiceDetailGroup $queryCriteriagroup,
        public Optional | DateTimeInterface $hidingDateFrom = new Optional(),
        public Optional | DateTimeInterface $hidingDateTo = new Optional(),
        public Optional | bool $isHidden = new Optional()
    ) {
    }

    /**
     * @return array<string|int, mixed>
     */
    public function toBody(KeyType $keyType = KeyType::Camel): array
    {
        /** @var array{queryCriteria: array<string, array<string, mixed>>} */
        $array = parent::toArray(KeyType::Camel);

        $array = array_merge($array, $this->queryCriteriagroup->toBody($keyType));

        unset($array['queryCriteriagroup']);

        return $array;
    }
}
