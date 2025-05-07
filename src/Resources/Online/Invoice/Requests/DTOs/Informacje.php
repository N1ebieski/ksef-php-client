<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs;

use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\StopkaFaktury;
use N1ebieski\KSEFClient\Support\DTO;

final readonly class Informacje extends DTO
{
    /**
     * @return void
     */
    public function __construct(
        public ?StopkaFaktury $stopkaFaktury = null,
    ) {
    }
}
