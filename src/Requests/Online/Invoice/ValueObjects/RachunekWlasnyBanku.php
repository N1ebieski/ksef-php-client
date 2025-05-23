<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects;

use N1ebieski\KSEFClient\Contracts\EnumInterface;

enum RachunekWlasnyBanku: string implements EnumInterface
{
    case WierzytelnosciPieniezne = '1';

    case PobranieNaleznosci = '2';

    case GospodarkaWlasna = '3';
}
