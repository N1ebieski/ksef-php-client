<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\DTOs;

use N1ebieski\KSEFClient\Contracts\BodyInterface;
use N1ebieski\KSEFClient\Requests\ValueObjects\SubjectIdentifierTo;
use N1ebieski\KSEFClient\Requests\ValueObjects\SubjectIdentifierToCompany;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\ValueObjects\KeyType;

final readonly class SubjectIdentifierToCompanyGroup extends AbstractDTO implements BodyInterface
{
    public function __construct(
        public SubjectIdentifierToCompany $subjectIdentifierToCompany,
        public SubjectIdentifierTo $type = SubjectIdentifierTo::Onip
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toBody(KeyType $keyType = KeyType::Camel): array
    {
        return [
            'type' => $this->type->value,
            'identifier' => $this->subjectIdentifierToCompany->value
        ];
    }
}
