<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\DTOs;

use N1ebieski\KSEFClient\Contracts\BodyInterface;
use N1ebieski\KSEFClient\Requests\DTOs\SubjectIdentifierBy;
use N1ebieski\KSEFClient\Requests\DTOs\SubjectName;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Concerns\HasToBody;

final readonly class SubjectBy extends AbstractDTO implements BodyInterface
{
    use HasToBody;

    public function __construct(
        public SubjectIdentifierBy $issuedByIdentifier,
        public SubjectName $issuedByName
    ) {
    }
}
