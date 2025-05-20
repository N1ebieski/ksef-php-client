<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\DTOs;

use N1ebieski\KSEFClient\Requests\ValueObjects\SubjectIdentifierInternal;
use N1ebieski\KSEFClient\Requests\ValueObjects\SubjectIdentifier;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class SubjectIdentifierInternalGroup extends AbstractDTO
{
    public SubjectIdentifier $type;

    public function __construct(
        public SubjectIdentifierInternal $identifier
    ) {
        $this->type = SubjectIdentifier::Int;
    }
}
