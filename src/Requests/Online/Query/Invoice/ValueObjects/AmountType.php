<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Query\Invoice\ValueObjects;

use N1ebieski\KSEFClient\Contracts\EnumInterface;

enum AmountType: string implements EnumInterface
{
    case Brutto = 'brutto';

    case Netto = 'netto';

    case Vat = 'vat';
}
