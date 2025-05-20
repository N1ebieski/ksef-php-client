<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Query\Invoice\Sync;

use N1ebieski\KSEFClient\Requests\AbstractRequest;
use N1ebieski\KSEFClient\Requests\Online\Query\Invoice\DTOs\QueryCriteria;
use N1ebieski\KSEFClient\Support\ValueObjects\KeyType;

final readonly class SyncRequest extends AbstractRequest
{
    public function __construct(
        public QueryCriteria $queryCriteria,
        public int $pageSize = 10,
        public int $pageOffset = 0,
    ) {
    }

    public function toBody(): array
    {
        $array = parent::toArray(KeyType::Camel);

        $array['queryCriteria'] = array_merge($array['queryCriteria'], $array['queryCriteria']['typeCriteriagroup']);

        unset($array['queryCriteria']['typeCriteriagroup']);
        unset($array['pageSize']);
        unset($array['pageOffset']);

        return $array;
    }
}
