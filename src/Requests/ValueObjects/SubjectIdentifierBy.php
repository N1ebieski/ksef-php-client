<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\ValueObjects;

use N1ebieski\KSEFClient\Contracts\EnumInterface;

enum SubjectIdentifierBy: string implements EnumInterface
{
    case Int = 'int';

    case Onip = 'onip';
}
