<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Query\Invoice\Sync;

use N1ebieski\KSEFClient\Contracts\BodyInterface;
use N1ebieski\KSEFClient\Requests\AbstractRequest;
use N1ebieski\KSEFClient\Requests\Online\Query\Invoice\DTOs\QueryCriteria;
use N1ebieski\KSEFClient\Support\Concerns\HasToBody;
use N1ebieski\KSEFClient\Support\ValueObjects\KeyType;

final readonly class SyncRequest extends AbstractRequest implements BodyInterface
{
    use HasToBody {
        toBody as parentToBody;
    }

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
        /** @var array{queryCriteria: array{queryCriteriaGroup: array<string, mixed>}, pageSize: int, pageOffset: int} */
        $array = $this->parentToBody($keyType);

        unset($array['pageSize']);
        unset($array['pageOffset']);

        return $array;
    }
}
