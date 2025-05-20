<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\DTOs;

use N1ebieski\KSEFClient\Contracts\BodyInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\ValueObjects\KeyType;

final readonly class SubjectIdentifierBy extends AbstractDTO implements BodyInterface
{
    public function __construct(
        public SubjectIdentifierInternalGroup | SubjectIdentifierByCompanyGroup $subjectIdentifierBygroup
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toBody(KeyType $keyType = KeyType::Camel): array
    {
        return $this->subjectIdentifierBygroup->toBody($keyType);
    }
}
