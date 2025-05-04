<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\DTOs\DTOs;

use N1ebieski\KSEFClient\Resources\Online\Invoice\DTOs\ValueObjects\AdresL1;
use N1ebieski\KSEFClient\Resources\Online\Invoice\DTOs\ValueObjects\AdresL2;
use N1ebieski\KSEFClient\Resources\Online\Invoice\DTOs\ValueObjects\Email;
use N1ebieski\KSEFClient\Resources\Online\Invoice\DTOs\ValueObjects\KodKraju;
use N1ebieski\KSEFClient\Resources\Online\Invoice\DTOs\ValueObjects\Nazwa;
use N1ebieski\KSEFClient\Resources\Online\Invoice\DTOs\ValueObjects\Telefon;
use N1ebieski\KSEFClient\Support\DTO;
use N1ebieski\KSEFClient\ValueObjects\Nip;

final readonly class DaneKontaktowe extends DTO
{
    public function __construct(
        public ?Email $email = null,
        public ?Telefon $telefon = null
    ) {
    }
}
