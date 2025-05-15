<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects;

use N1ebieski\KSEFClient\Contracts\EnumInterface;

enum P_16: string implements EnumInterface
{
    case MetodaKasowa = '1';

    case Default = '2';
}
