<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\DTOs;

use N1ebieski\KSEFClient\Contracts\BodyInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\ValueObjects\KeyType;

final readonly class SubjectIdentifierTo extends AbstractDTO implements BodyInterface
{
    public function __construct(
        public SubjectIdentifierToNoneGroup | SubjectIdentifierToVatUeGroup | SubjectIdentifierToCompanyGroup | SubjectIdentifierToOtherGroup $subjectIdentifierToGroup
    ) {
    }

    /**
     * @return array<string|int, mixed>
     */
    public function toBody(KeyType $keyType = KeyType::Camel): array
    {
        return $this->subjectIdentifierToGroup->toBody($keyType);
    }
}
