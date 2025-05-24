<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Query\Invoice\Sync;

use N1ebieski\KSEFClient\Contracts\BodyInterface;
use N1ebieski\KSEFClient\Contracts\ParametersInterface;
use N1ebieski\KSEFClient\Requests\AbstractRequest;
use N1ebieski\KSEFClient\Requests\Online\Query\Invoice\DTOs\QueryCriteria;
use N1ebieski\KSEFClient\Requests\Online\Query\Invoice\ValueObjects\PageOffset;
use N1ebieski\KSEFClient\Requests\Online\Query\Invoice\ValueObjects\PageSize;
use N1ebieski\KSEFClient\Support\Concerns\HasToBody;
use N1ebieski\KSEFClient\Support\ValueObjects\KeyType;

final readonly class SyncRequest extends AbstractRequest implements BodyInterface, ParametersInterface
{
    use HasToBody {
        toBody as parentToBody;
    }

    public function __construct(
        public QueryCriteria $queryCriteria,
        public PageSize $pageSize = new PageSize(10),
        public PageOffset $pageOffset = new PageOffset(0),
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toParameters(KeyType $keyType = KeyType::Camel): array
    {
        return [
            'PageSize' => $this->pageSize->value,
            'PageOffset' => $this->pageOffset->value
        ];
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
