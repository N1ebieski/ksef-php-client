<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Permissions\Query\Entities\Grants;

use N1ebieski\KSEFClient\Contracts\BodyInterface;
use N1ebieski\KSEFClient\Contracts\ParametersInterface;
use N1ebieski\KSEFClient\DTOs\Requests\Permissions\ContextIdentifierInternalIdGroup;
use N1ebieski\KSEFClient\DTOs\Requests\Permissions\ContextIdentifierNipGroup;
use N1ebieski\KSEFClient\Requests\AbstractRequest;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\ValueObjects\Requests\PageOffset;
use N1ebieski\KSEFClient\ValueObjects\Requests\PageSize;

final class GrantsRequest extends AbstractRequest implements BodyInterface, ParametersInterface
{
    public function __construct(
        public readonly Optional | ContextIdentifierNipGroup | ContextIdentifierInternalIdGroup $contextIdentifierGroup = new Optional(),
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

        if ( ! $this->contextIdentifierGroup instanceof Optional) {
            $data['contextIdentifier'] = [
                'type' => $this->contextIdentifierGroup->getIdentifier()->getType(),
                'value' => (string) $this->contextIdentifierGroup->getIdentifier(),
            ];
        }

        return $data;
    }
}
