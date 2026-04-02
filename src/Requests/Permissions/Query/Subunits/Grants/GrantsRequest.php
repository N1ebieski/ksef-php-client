<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Permissions\Query\Subunits\Grants;

use N1ebieski\KSEFClient\Contracts\BodyInterface;
use N1ebieski\KSEFClient\Contracts\ParametersInterface;
use N1ebieski\KSEFClient\DTOs\Requests\Permissions\SubunitIdentifierInternalIdGroup;
use N1ebieski\KSEFClient\Requests\AbstractRequest;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\ValueObjects\Requests\PageOffset;
use N1ebieski\KSEFClient\ValueObjects\Requests\PageSize;

final class GrantsRequest extends AbstractRequest implements BodyInterface, ParametersInterface
{
    public function __construct(
        public readonly Optional | SubunitIdentifierInternalIdGroup $subunitIdentifierGroup = new Optional(),
        public readonly Optional | PageOffset $pageOffset = new Optional(),
        public readonly Optional | PageSize $pageSize = new Optional(),
    ) {
    }

    public function toParameters(): array
    {
        /** @var array<string, mixed> */
        return $this->toArray(only: ['pageSize', 'pageOffset']);
    }

    public function toBody(): array
    {
        /** @var array<string, mixed> $data */
        $data = [];

        if ( ! $this->subunitIdentifierGroup instanceof Optional) {
            $data['subunitIdentifier'] = [
                'type' => $this->subunitIdentifierGroup->getIdentifier()->getType(),
                'value' => (string) $this->subunitIdentifierGroup->getIdentifier(),
            ];
        }

        return $data;
    }
}
