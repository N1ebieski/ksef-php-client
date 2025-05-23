<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects;

use N1ebieski\KSEFClient\Contracts\EnumInterface;

enum P_12Z: string implements EnumInterface
{
    case Tax23 = '23';

    case Tax22 = '22';

    case Tax8 = '8';

    case Tax7 = '7';

    case Tax5 = '5';

    case Tax4 = '4';

    case Tax3 = '3';

    case Tax0 = '0';

    case Zw = 'zw';

    case Oo = 'oo';

    case Np = 'np';
}
