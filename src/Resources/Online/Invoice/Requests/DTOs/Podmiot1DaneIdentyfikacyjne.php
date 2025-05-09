<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs;

use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\Nazwa;
use N1ebieski\KSEFClient\Support\DTO;
use N1ebieski\KSEFClient\ValueObjects\NIP;

final readonly class Podmiot1DaneIdentyfikacyjne extends DTO
{
    public function __construct(
        public NIP $nip,
        public Nazwa $nazwa
    ) {
    }
}
