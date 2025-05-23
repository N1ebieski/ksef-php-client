<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects;

use N1ebieski\KSEFClient\Contracts\EnumInterface;

enum OpisLadunku: string implements EnumInterface
{
    case Banka = '1';

    case Beczka = '2';

    case Butla = '3';

    case Karton = '4';

    case Kanister = '5';

    case Klatka = '6';

    case Kontener = '7';

    case KoszKoszyk = '8';

    case Lubianka = '9';

    case OpakowanieZbiorcze = '10';

    case Paczka = '11';

    case Pakiet = '12';

    case Paleta = '13';

    case Pojemnik = '14';

    case PojemnikMasowyStaly = '15';

    case PojemnikMasowyPlynny = '16';

    case Pudelko = '17';

    case Puszka = '18';

    case Skrzynia = '19';

    case Worek = '20';
}
