<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\DTOs;

use N1ebieski\KSEFClient\Contracts\BodyInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Concerns\HasToBody;

final readonly class SubjectTo extends AbstractDTO implements BodyInterface
{
    use HasToBody;

    public function __construct(
        public SubjectIdentifierTo $issuedToIdentifier,
        public SubjectName $issuedToName
    ) {
    }
}
