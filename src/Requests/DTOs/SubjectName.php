<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\DTOs;

use N1ebieski\KSEFClient\Contracts\BodyInterface;
use N1ebieski\KSEFClient\Requests\ValueObjects\TradeName;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\Support\ValueObjects\KeyType;

final readonly class SubjectName extends AbstractDTO implements BodyInterface
{
    public function __construct(
        public SubjectFullNameGroup | SubjectPersonNameGroup | SubjectNoneGroup $subjectNameGroup,
        public Optional | TradeName | null $tradeName = new Optional()
    ) {
    }

    /**
     * @return array<string|int, mixed>
     */
    public function toBody(KeyType $keyType = KeyType::Camel): array
    {
        $array = $this->toArray(KeyType::Camel);

        $array = array_merge($array, $this->subjectNameGroup->toBody($keyType));

        unset($array['subjectNameGroup']);

        return $array;
    }
}
