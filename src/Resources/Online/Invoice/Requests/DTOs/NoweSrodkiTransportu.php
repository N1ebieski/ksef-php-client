<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs;

use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_22N;
use N1ebieski\KSEFClient\Support\DTO;

final readonly class NoweSrodkiTransportu extends DTO
{
    public function __construct(
        public P_22Group | P_22NGroup $p_22group = new P_22NGroup(),
    ) {
    }
}
