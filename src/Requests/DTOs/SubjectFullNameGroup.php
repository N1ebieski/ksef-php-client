<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\DTOs;

use N1ebieski\KSEFClient\Requests\ValueObjects\SubjectFullName;
use N1ebieski\KSEFClient\Requests\ValueObjects\SubjectName;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class SubjectFullNameGroup extends AbstractDTO
{
    public SubjectName $type;

    public function __construct(
        public SubjectFullName $fullName,
    ) {
        $this->type = SubjectName::Fn;
    }
}
