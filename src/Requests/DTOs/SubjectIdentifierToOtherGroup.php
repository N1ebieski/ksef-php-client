<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\DTOs;

use N1ebieski\KSEFClient\Contracts\BodyInterface;
use N1ebieski\KSEFClient\Requests\ValueObjects\SubjectIdentifierTo;
use N1ebieski\KSEFClient\Requests\ValueObjects\SubjectIdentifierToOther;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\ValueObjects\KeyType;

final readonly class SubjectIdentifierToOtherGroup extends AbstractDTO implements BodyInterface
{
    public function __construct(
        public SubjectIdentifierToOther $subjectIdentifierToOther,
        public SubjectIdentifierTo $type = SubjectIdentifierTo::Other
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toBody(KeyType $keyType = KeyType::Camel): array
    {
        return [
            'type' => $this->type->value,
            'identifier' => $this->subjectIdentifierToOther->value
        ];
    }
}
