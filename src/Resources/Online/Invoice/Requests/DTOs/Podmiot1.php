<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs;

use N1ebieski\KSEFClient\Support\DTO;

final readonly class Podmiot1 extends DTO
{
    public function __construct(
        public DaneIdentyfikacyjne $daneIdentyfikacyjne,
        public Adres $adres,
        public ?DaneKontaktowe $daneKontaktowe = null
    ) {
    }
}
