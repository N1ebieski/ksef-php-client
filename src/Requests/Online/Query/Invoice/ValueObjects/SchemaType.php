<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Query\Invoice\ValueObjects;

use N1ebieski\KSEFClient\Contracts\EnumInterface;

enum SchemaType: string implements EnumInterface
{
    case Vat = 'VAT';

    case VatRr = 'VAT_RR';

    case Pef = 'PEF';
}
