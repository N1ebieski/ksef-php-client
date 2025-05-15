<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\StopkaFaktury;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class Informacje extends AbstractDTO
{
    /**
     * @return void
     */
    public function __construct(
        public ?StopkaFaktury $stopkaFaktury = null,
    ) {
    }
}
