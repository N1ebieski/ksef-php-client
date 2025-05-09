<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs;

use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_19N;
use N1ebieski\KSEFClient\Support\DTO;

final readonly class Zwolnienie extends DTO
{
    public function __construct(
        public P_19Group | P_19NGroup $p_19group = new P_19NGroup(),
    ) {
    }
}
