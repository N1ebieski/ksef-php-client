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

    /**
     * @return array<string, mixed>
     */
    public function toBody(KeyType $keyType = KeyType::Camel): array
    {
        /** @var array{queryCriteria: array{queryCriteriagroup: array<string, mixed>}, pageSize: int, pageOffset: int} */
        $array = parent::toBody($keyType);

        unset($array['pageSize']);
        unset($array['pageOffset']);

        return $array;
    }
}
