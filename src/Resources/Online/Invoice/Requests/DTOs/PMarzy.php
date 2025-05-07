<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs;

use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_PMarzyN;
use N1ebieski\KSEFClient\Support\DTO;

final readonly class PMarzy extends DTO
{
    /**
     * @param P_PMarzyN|null $p_PMarzyN Znacznik braku wystąpienia procedur marży, o których mowa w art. 119 lub art. 120 ustawy
     */
    public function __construct(
        public ?P_PMarzyN $p_PMarzyN = null,
    ) {
    }
}
