<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\DTOs;

use N1ebieski\KSEFClient\Contracts\BodyInterface;
use N1ebieski\KSEFClient\Requests\ValueObjects\SubjectIdentifierTo;
use N1ebieski\KSEFClient\Requests\ValueObjects\SubjectIdentifierToVatUe;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\ValueObjects\KeyType;

final readonly class SubjectIdentifierToVatUeGroup extends AbstractDTO implements BodyInterface
{
    public SubjectIdentifierTo $type;

    public function __construct(
        public SubjectIdentifierToVatUe $subjectIdentifierToVatUe
    ) {
        $this->type = SubjectIdentifierTo::VatUe;
    }

    /**
     * @return array<string, mixed>
     */
    public function toBody(KeyType $keyType = KeyType::Camel): array
    {
        return [
            'type' => $this->type->value,
            'identifier' => $this->subjectIdentifierToVatUe->value
        ];
    }
}
