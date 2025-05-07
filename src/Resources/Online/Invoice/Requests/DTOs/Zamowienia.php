<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs;

use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\DataZamowienia;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\NrZamowienia;
use N1ebieski\KSEFClient\Support\DTO;

final readonly class Zamowienia extends DTO
{
    public function __construct(
        public ?DataZamowienia $dataZamowienia = null,
        public ?NrZamowienia $nrZamowienia = null,
    ) {
    }
}
