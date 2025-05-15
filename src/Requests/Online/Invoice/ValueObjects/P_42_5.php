<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects;

use N1ebieski\KSEFClient\Contracts\EnumInterface;

enum P_42_5: string implements EnumInterface
{
    case DokumentWywozu = '1';

    case Default = '2';
}
