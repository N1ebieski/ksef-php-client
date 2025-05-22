<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects;

use N1ebieski\KSEFClient\Contracts\EnumInterface;

enum NrKSeFZN: string implements EnumInterface
{
    case Default = '1';
}
