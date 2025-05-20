<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Query\Invoice\ValueObjects;

use N1ebieski\KSEFClient\Contracts\EnumInterface;

enum InvoiceType: string implements EnumInterface
{
    case Vat = 'VAT';

    case Kor = 'KOR';

    case Zal = 'ZAL';

    case Roz = 'ROZ';

    case Upr = 'UPR';

    case KorZal = 'KOR_ZAL';

    case KorRoz = 'KOR_ROZ';

    case VatPef = 'VAT_PEF';

    case VatPefSp = 'VAT_PEF_SP';

    case KorPef = 'KOR_PEF';
}
