<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs;

use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\NrWierszaFa;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_11;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_12;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_7;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_8A;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_8B;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_9A;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\UU_ID;
use N1ebieski\KSEFClient\Support\DTO;

final readonly class FaWiersz extends DTO
{
    /**
     * @param UU_ID|null $uu_id Uniwersalny unikalny numer wiersza faktury
     * @param P_7|null $p_7 Nazwa (rodzaj) towaru lub usługi. Pole opcjonalne wyłącznie dla przypadku określonego w art 106j ust. 3 pkt 2 ustawy (faktura korygująca)
     * @param P_8A|null $p_8a Miara dostarczonych towarów lub zakres wykonanych usług. Pole opcjonalne dla przypadku określonego w art. 106e ust. 5 pkt 3 ustawy
     * @param P_8B|null $p_8b Ilość (liczba) dostarczonych towarów lub zakres wykonanych usług. Pole opcjonalne dla przypadku określonego w art. 106e ust. 5 pkt 3 ustawy
     * @param P_9A|null $p_9a Cena jednostkowa towaru lub usługi bez kwoty podatku (cena jednostkowa netto). Pole opcjonalne dla przypadków określonych w art. 106e ust. 2 i 3 oraz ust. 5 pkt 3 ustawy
     * @param P_11|null $p_11 Wartość dostarczonych towarów lub wykonanych usług, objętych transakcją, bez kwoty podatku (wartość sprzedaży netto). Pole opcjonalne dla przypadków określonych w art. 106e ust. 2 i 3 oraz ust. 5 pkt 3 ustawy
     * @param P_12|null $p_12 Stawka podatku. Pole opcjonalne dla przypadków określonych w art. 106e ust. 2, 3, ust. 4 pkt 3 i ust. 5 pkt 3 ustawy
     * @return void
     */
    public function __construct(
        public ?UU_ID $uu_id = null,
        public ?P_7 $p_7 = null,
        public ?P_8A $p_8a = null,
        public ?P_8B $p_8b = null,
        public ?P_9A $p_9a = null,
        public ?P_11 $p_11 = null,
        public ?P_12 $p_12 = null
    ) {
    }
}
