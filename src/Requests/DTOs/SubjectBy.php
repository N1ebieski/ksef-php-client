<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\DTOs;

use N1ebieski\KSEFClient\Requests\DTOs\SubjectIdentifierBy;
use N1ebieski\KSEFClient\Requests\DTOs\SubjectName;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class SubjectBy extends AbstractDTO
{
    public function __construct(
        public SubjectIdentifierBy $issuedByIdentifier,
        public SubjectName $issuedByName
    ) {
    }
}
