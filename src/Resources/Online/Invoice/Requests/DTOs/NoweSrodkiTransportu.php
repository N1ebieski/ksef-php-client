<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs;

use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_22N;
use N1ebieski\KSEFClient\Support\DTO;

final readonly class NoweSrodkiTransportu extends DTO
{
    /**
     * @param P_22N|null $p_22N Znacznik braku wewnątrzwspólnotowej dostawy nowych środków transportu
     * @return void
     */
    public function __construct(
        public ?P_22N $p_22N = null,
    ) {
    }
}
