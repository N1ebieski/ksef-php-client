<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects;

use N1ebieski\KSEFClient\Contracts\EnumInterface;

enum TypKorekty: string implements EnumInterface
{
    case Pierwotna = '1';

    case Korygujaca = '2';

    case Inna = '3';
}
