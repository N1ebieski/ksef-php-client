<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects;

use N1ebieski\KSEFClient\Contracts\EnumInterface;

enum FormaPlatnosci: string implements EnumInterface
{
    case Gotowka = '1';

    case Karta = '2';

    case Bon = '3';

    case Czek = '4';

    case Kredyt = '5';

    case Przelew = '6';

    case Mobilna = '7';
}
