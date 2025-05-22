<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects;

use N1ebieski\KSEFClient\Contracts\EnumInterface;

enum Procedura: string implements EnumInterface
{
    case WstoEe = 'WSTO_EE';

    case Ied = 'IED';

    case TtD = 'TT_D';

    case I42 = 'I_42';

    case I63 = 'I_63';

    case BSpv = 'B_SPV';

    case BSpvDostawa = 'B_SPV_DOSTAWA';

    case BMpvDostawa = 'B_MPV_DOSTAWA';
}
