<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\DTOs;

use N1ebieski\KSEFClient\Contracts\BodyInterface;
use N1ebieski\KSEFClient\Requests\ValueObjects\SubjectIdentifierBy;
use N1ebieski\KSEFClient\Requests\ValueObjects\SubjectIdentifierInternal;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\ValueObjects\KeyType;

final readonly class SubjectIdentifierInternalGroup extends AbstractDTO implements BodyInterface
{
    public function __construct(
        public SubjectIdentifierInternal $subjectIdentifierInternal,
        public SubjectIdentifierBy $type = SubjectIdentifierBy::Int
    ) {
    }

    public function toBody(KeyType $keyType = KeyType::Camel): array
    {
        return [
            'type' => $this->type->value,
            'identifier' => $this->subjectIdentifierInternal->value,
        ];
    }
}
