<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\DTOs;

use N1ebieski\KSEFClient\Contracts\BodyInterface;
use N1ebieski\KSEFClient\Requests\ValueObjects\SubjectIdentifierTo;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Concerns\HasToBody;

final readonly class SubjectIdentifierToNoneGroup extends AbstractDTO implements BodyInterface
{
    use HasToBody;

    public SubjectIdentifierTo $type;

    public function __construct()
    {
        $this->type = SubjectIdentifierTo::None;
    }
}
