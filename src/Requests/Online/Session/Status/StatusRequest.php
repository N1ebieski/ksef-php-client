<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Session\Status;

use N1ebieski\KSEFClient\Contracts\ParametersInterface;
use N1ebieski\KSEFClient\Requests\AbstractRequest;
use N1ebieski\KSEFClient\Requests\Online\Session\ValueObjects\PageOffset;
use N1ebieski\KSEFClient\Requests\Online\Session\ValueObjects\PageSize;
use N1ebieski\KSEFClient\Requests\ValueObjects\ReferenceNumber;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\Support\ValueObjects\KeyType;

final readonly class StatusRequest extends AbstractRequest implements ParametersInterface
{
    public function __construct(
        public Optional | ReferenceNumber $referenceNumber = new Optional(),
        public Optional | PageSize $pageSize = new Optional(),
        public Optional | PageOffset $pageOffset = new Optional(),
        public Optional | bool $includeDetails = new Optional()
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toParameters(KeyType $keyType = KeyType::Camel): array
    {
        /** @var array<string, mixed> */
        return $this->toArray(only: ['pageSize', 'pageOffset', 'includeDetails']);
    }
}
