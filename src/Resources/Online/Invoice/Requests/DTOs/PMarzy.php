<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs;

use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_PMarzyN;
use N1ebieski\KSEFClient\Support\DTO;

final readonly class PMarzy extends DTO
{
    public function __construct(
        public P_PMarzyGroup | P_PMarzyNGroup $p_pMarzygroup = new P_PMarzyNGroup(),
    ) {
    }
}
