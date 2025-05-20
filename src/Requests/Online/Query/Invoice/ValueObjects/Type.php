<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Query\Invoice\ValueObjects;

use N1ebieski\KSEFClient\Contracts\EnumInterface;

enum Type: string implements EnumInterface
{
    case Detail = 'detail';

    case Incremental = 'incremental';

    case Range = 'range';
}
