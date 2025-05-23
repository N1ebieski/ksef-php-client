<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects;

use N1ebieski\KSEFClient\Contracts\EnumInterface;

enum RodzajTransportu: string implements EnumInterface
{
    case Morski = '1';

    case Kolejowy = '2';

    case Drogowy = '3';

    case Lotniczy = '4';

    case PrzesylkaPocztowa = '5';

    case StaleInstalacjePrzesylowe = '6';

    case ZeglugaSrodladowa = '7';
}
