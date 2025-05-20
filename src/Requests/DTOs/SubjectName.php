<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\DTOs;

use N1ebieski\KSEFClient\Requests\ValueObjects\TradeName;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;

final readonly class SubjectName extends AbstractDTO
{
    public function __construct(
        public SubjectFullNameGroup | SubjectPersonNameGroup | SubjectNoneGroup $subjectNamegroup,
        public Optional | TradeName | null $tradeName = new Optional()
    ) {
    }
}
