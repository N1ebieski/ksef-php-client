<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects;

use N1ebieski\KSEFClient\Contracts\EnumInterface;

enum Rola: string implements EnumInterface
{
    case Faktor = '1';

    case Odbiorca = '2';

    case PodmiotPierwotny = '3';

    case DodatkowyNabywca = '4';

    case WystawcaFaktury = '5';

    case DokonujacyPlatnosci = '6';

    case JednostkaSamorzaduTerytorialnegoWystawca = '7';

    case JednostkaSamorzaduTerytorialnegoOdbiorca = '8';

    case CzlonekGrupyVATWystawca = '9';

    case CzlonekGrupyVATOdbiorca = '10';
}
