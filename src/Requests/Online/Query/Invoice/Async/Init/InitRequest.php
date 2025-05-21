<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Query\Invoice\Async\Init;

use N1ebieski\KSEFClient\Contracts\BodyInterface;
use N1ebieski\KSEFClient\Requests\AbstractRequest;
use N1ebieski\KSEFClient\Requests\Online\Query\Invoice\DTOs\QueryCriteria;
use N1ebieski\KSEFClient\Support\Concerns\HasToBody;

final readonly class InitRequest extends AbstractRequest implements BodyInterface
{
    use HasToBody;

    public function __construct(
        public QueryCriteria $queryCriteria,
    ) {
    }
}
