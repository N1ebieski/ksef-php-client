<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects;

use N1ebieski\KSEFClient\Contracts\EnumInterface;

enum StatusInfoPodatnika: string implements EnumInterface
{
    case Likwidacja = '1';

    case Restrukturyzacja = '2';

    case Upadlosc = '3';

    case Spadek = '4';
}
