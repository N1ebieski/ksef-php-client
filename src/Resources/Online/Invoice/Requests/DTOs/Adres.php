<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs;

use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\AdresL1;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\AdresL2;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\KodKraju;
use N1ebieski\KSEFClient\Support\DTO;

final readonly class Adres extends DTO
{
    public function __construct(
        public AdresL1 $adresL1,
        public KodKraju $kodKraju = new KodKraju('PL'),
        public ?AdresL2 $adresL2 = null,
    ) {
    }
}
