<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects;

use N1ebieski\KSEFClient\Contracts\EnumInterface;

enum PodmiotPosredniczacy: string implements EnumInterface
{
    case Defaul = '1';
}
