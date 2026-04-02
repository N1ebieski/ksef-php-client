<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Permissions\Query\Authorizations\Grants;

use N1ebieski\KSEFClient\Contracts\BodyInterface;
use N1ebieski\KSEFClient\Contracts\ParametersInterface;
use N1ebieski\KSEFClient\DTOs\Requests\Permissions\AuthorizedIdentifierNipGroup;
use N1ebieski\KSEFClient\DTOs\Requests\Permissions\AuthorizedIdentifierPeppolIdGroup;
use N1ebieski\KSEFClient\DTOs\Requests\Permissions\AuthorizingIdentifierNipGroup;
use N1ebieski\KSEFClient\Requests\AbstractRequest;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\ValueObjects\Requests\PageOffset;
use N1ebieski\KSEFClient\ValueObjects\Requests\PageSize;
use N1ebieski\KSEFClient\ValueObjects\Requests\Permissions\Authorizations\AuthorizationPermissionType;
use N1ebieski\KSEFClient\ValueObjects\Requests\Permissions\Authorizations\QueryType;

final class GrantsRequest extends AbstractRequest implements ParametersInterface, BodyInterface
{
    /**
     * @param Optional|array<int, AuthorizationPermissionType> $permissionTypes
     */
    public function __construct(
        public readonly QueryType $queryType,
        public readonly Optional | AuthorizingIdentifierNipGroup $authorizingIdentifierGroup = new Optional(),
        public readonly Optional | AuthorizedIdentifierNipGroup | AuthorizedIdentifierPeppolIdGroup $authorizedIdentifierGroup = new Optional(),
        public readonly Optional | array $permissionTypes = new Optional(),
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
        $data = $this->toArray(only: ['queryType', 'permissionTypes']);

        if ( ! $this->authorizingIdentifierGroup instanceof Optional) {
            $data['authorizingIdentifier'] = [
                'type' => $this->authorizingIdentifierGroup->getIdentifier()->getType(),
                'value' => (string) $this->authorizingIdentifierGroup->getIdentifier(),
            ];
        }

        if ( ! $this->authorizedIdentifierGroup instanceof Optional) {
            $data['authorizedIdentifier'] = [
                'type' => $this->authorizedIdentifierGroup->getIdentifier()->getType(),
                'value' => (string) $this->authorizedIdentifierGroup->getIdentifier(),
            ];
        }

        return $data;
    }
}
