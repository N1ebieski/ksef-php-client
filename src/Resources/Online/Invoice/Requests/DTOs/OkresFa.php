<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs;

use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_22N;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_6_Do;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_6_Od;
use N1ebieski\KSEFClient\Support\DTO;

final readonly class OkresFa extends DTO
{
    /**
     * @param P_6_Od $p_6_od Data początkowa okresu, którego dotyczy faktura
     * @param P_6_Do $p_6_do Data końcowa okresu, którego dotyczy faktura - data dokonania lub zakończenia dostawy towarów lub wykonania usługi
     * @return void
     */
    public function __construct(
        public P_6_Od $p_6_od,
        public P_6_Do $p_6_do
    ) {
    }
}
