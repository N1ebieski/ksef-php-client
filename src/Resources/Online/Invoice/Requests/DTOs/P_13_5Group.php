<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs;

use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\KodKraju;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\KodUE;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\Nazwa;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\NrID;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\NrVatUE;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_13_1;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_13_3;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_13_4;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_13_5;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_14_1;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_14_3;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_14_4;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_14_5;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_6;
use N1ebieski\KSEFClient\Support\DTO;
use N1ebieski\KSEFClient\ValueObjects\NIP;

final readonly class P_13_5Group extends DTO
{
    /**
     * @param P_13_5 $p_13_4 Suma wartości sprzedaży netto w przypadku procedury szczególnej, o której mowa w dziale XII w rozdziale 6a ustawy. W przypadku faktur zaliczkowych, kwota zaliczki netto. W przypadku faktur korygujących, kwota różnicy, o której mowa w art. 106j ust. 2 pkt 5 ustawy
     * @param P_14_5|null $p_14_4 Kwota podatku od wartości dodanej w przypadku procedury szczególnej, o której mowa w dziale XII w rozdziale 6a ustawy. W przypadku faktur zaliczkowych, kwota podatku wyliczona według wzoru, o którym mowa w art. 106f ust. 1 pkt 3 ustawy. W przypadku faktur korygujących, kwota różnicy, o której mowa w art. 106j ust. 2 pkt 5 ustawy
     * @return void
     */
    public function __construct(
        public P_13_5 $p_13_5,
        public ?P_14_5 $p_14_5 = null,
    ) {
    }
}
