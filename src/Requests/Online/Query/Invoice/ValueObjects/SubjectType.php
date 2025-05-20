<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Query\Invoice\ValueObjects;

use N1ebieski\KSEFClient\Contracts\EnumInterface;

enum SubjectType: string implements EnumInterface
{
    case Subject1 = 'subject1';

    case Subject2 = 'subject2';

    case Subject3 = 'subject3';

    case subjectAuthorized = 'subjectAuthorized';
}
