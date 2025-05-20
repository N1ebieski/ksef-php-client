<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\ValueObjects;

use N1ebieski\KSEFClient\Contracts\EnumInterface;

enum SubjectIdentifierTo: string implements EnumInterface
{
    case None = 'none';

    case Onip = 'onip';

    case Other = 'other';

    case VatUe = 'vatUE';
}
