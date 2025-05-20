<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\ValueObjects;

use N1ebieski\KSEFClient\Contracts\EnumInterface;

enum SubjectName: string implements EnumInterface
{
    case Fn = 'fn';

    case None = 'none';

    case Pn = 'pn';
}
