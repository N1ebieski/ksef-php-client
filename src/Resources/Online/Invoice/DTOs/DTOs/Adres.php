<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\DTOs\DTOs;

use N1ebieski\KSEFClient\Resources\Online\Invoice\DTOs\ValueObjects\AdresL1;
use N1ebieski\KSEFClient\Resources\Online\Invoice\DTOs\ValueObjects\AdresL2;
use N1ebieski\KSEFClient\Resources\Online\Invoice\DTOs\ValueObjects\KodKraju;
use N1ebieski\KSEFClient\Support\DTO;

final readonly class Adres extends DTO
{
    public function __construct(
        public AdresL1 $adresL1,
        public ?KodKraju $kodKraju = null,
        public ?AdresL2 $adresL2 = null,
    ) {
    }
}
