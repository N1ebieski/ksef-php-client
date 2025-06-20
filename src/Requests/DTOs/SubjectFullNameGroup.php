<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\DTOs;

use N1ebieski\KSEFClient\Contracts\BodyInterface;
use N1ebieski\KSEFClient\Requests\ValueObjects\SubjectFullName;
use N1ebieski\KSEFClient\Requests\ValueObjects\SubjectName;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\ValueObjects\KeyType;

final readonly class SubjectFullNameGroup extends AbstractDTO implements BodyInterface
{
    public function __construct(
        public SubjectFullName $subjectFullName,
        public SubjectName $type = SubjectName::Fn
    ) {
    }

    public function toBody(KeyType $keyType = KeyType::Camel): array
    {
        return [
            'type' => $this->type->value,
            'fullName' => $this->subjectFullName->value,
        ];
    }
}
