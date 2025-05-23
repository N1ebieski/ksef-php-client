<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects;

use N1ebieski\KSEFClient\Contracts\EnumInterface;

enum GTUZ: string implements EnumInterface
{
    case Gtu01 = 'GTU_01';

    case Gtu02 = 'GTU_02';

    case Gtu03 = 'GTU_03';

    case Gtu04 = 'GTU_04';

    case Gtu05 = 'GTU_05';

    case Gtu06 = 'GTU_06';

    case Gtu07 = 'GTU_07';

    case Gtu08 = 'GTU_08';

    case Gtu09 = 'GTU_09';

    case Gtu10 = 'GTU_10';

    case Gtu11 = 'GTU_11';

    case Gtu12 = 'GTU_12';

    case Gtu13 = 'GTU_13';
}
