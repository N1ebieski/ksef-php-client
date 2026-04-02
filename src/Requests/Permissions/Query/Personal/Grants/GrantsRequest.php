<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Permissions\Query\Personal\Grants;

use N1ebieski\KSEFClient\Contracts\BodyInterface;
use N1ebieski\KSEFClient\Contracts\ParametersInterface;
use N1ebieski\KSEFClient\DTOs\Requests\Permissions\ContextIdentifierNipGroup;
use N1ebieski\KSEFClient\DTOs\Requests\Permissions\TargetIdentifierInternalIdGroup;
use N1ebieski\KSEFClient\DTOs\Requests\Permissions\TargetIdentifierNipGroup;
use N1ebieski\KSEFClient\DTOs\Requests\Permissions\TargetIdentifierTypeGroup;
use N1ebieski\KSEFClient\Requests\AbstractRequest;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\ValueObjects\Requests\PageOffset;
use N1ebieski\KSEFClient\ValueObjects\Requests\PageSize;
use N1ebieski\KSEFClient\ValueObjects\Requests\Permissions\Query\PermissionState;
use N1ebieski\KSEFClient\ValueObjects\Requests\Permissions\Query\Personal\PersonalPermissionType;

final class GrantsRequest extends AbstractRequest implements BodyInterface, ParametersInterface
{
    /**
     * @param Optional|array<int, PersonalPermissionType> $permissionTypes
     */
    public function __construct(
        public readonly Optional | ContextIdentifierNipGroup $contextIdentifierGroup = new Optional(),
        public readonly Optional | TargetIdentifierNipGroup | TargetIdentifierInternalIdGroup | TargetIdentifierTypeGroup $targetIdentifierGroup = new Optional(),
        public readonly Optional | array $permissionTypes = new Optional(),
        public readonly Optional | PermissionState $permissionState = new Optional(),
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
        $data = $this->toArray(only: ['permissionTypes', 'permissionState']);

        if ( ! $this->contextIdentifierGroup instanceof Optional) {
            $data['contextIdentifier'] = [
                'type' => $this->contextIdentifierGroup->getIdentifier()->getType(),
                'value' => (string) $this->contextIdentifierGroup->getIdentifier(),
            ];
        }

        if ( ! $this->targetIdentifierGroup instanceof Optional) {
            $data['targetIdentifier'] = [
                'type' => $this->targetIdentifierGroup->getIdentifier()->getType(),
                ...($this->targetIdentifierGroup instanceof TargetIdentifierTypeGroup ? [] : [
                    'value' => (string) $this->targetIdentifierGroup->getIdentifier()
                ]),
            ];
        }

        return $data;
    }
}
