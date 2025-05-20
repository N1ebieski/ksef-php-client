<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\DTOs;

use N1ebieski\KSEFClient\Contracts\BodyInterface;
use N1ebieski\KSEFClient\Requests\ValueObjects\SubjectName;
use N1ebieski\KSEFClient\Requests\ValueObjects\SubjectPersonName;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\ValueObjects\KeyType;

final readonly class SubjectPersonNameGroup extends AbstractDTO implements BodyInterface
{
    public SubjectName $type;

    public function __construct(
        public SubjectPersonName $subjectPersonName,
    ) {
        $this->type = SubjectName::Pn;
    }

    /**
     * @return array<string|int, mixed>
     */
    public function toBody(KeyType $keyType = KeyType::Camel): array
    {
        return [
            'type' => $this->type->value,
            ...$this->subjectPersonName->toArray($keyType),
        ];
    }
}
