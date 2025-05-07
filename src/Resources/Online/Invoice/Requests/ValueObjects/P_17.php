<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects;

use N1ebieski\KSEFClient\Contracts\EnumInterface;

enum P_17: string implements EnumInterface
{
    case Samofakturowanie = '1';

    case Default = '2';
}
