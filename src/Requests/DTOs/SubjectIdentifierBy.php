<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\DTOs;

use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class SubjectIdentifierBy extends AbstractDTO
{
    public function __construct(
        public SubjectIdentifierInternalGroup | SubjectIdentifierByCompanyGroup $subjectIdentifiergroup
    ) {
    }
}
