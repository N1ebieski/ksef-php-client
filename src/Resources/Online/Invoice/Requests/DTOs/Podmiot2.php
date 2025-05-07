<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs;

use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\NrKlienta;
use N1ebieski\KSEFClient\Support\DTO;

final readonly class Podmiot2 extends DTO
{
    /**
     * @param DaneIdentyfikacyjne $daneIdentyfikacyjne Dane identyfikujące nabywcę
     * @param Adres $adres Adres nabywcy
     * @param array<int, DaneKontaktowe> $daneKontaktowe Dane kontaktowe nabywcy
     * @param null|NrKlienta $nrKlienta Numer klienta dla przypadków, w których nabywca posługuje się nim w umowie lub zamówieniu
     * @return void
     */
    public function __construct(
        public DaneIdentyfikacyjne $daneIdentyfikacyjne,
        public ?Adres $adres = null,
        public ?DaneKontaktowe $daneKontaktowe = null,
        public ?NrKlienta $nrKlienta = null
    ) {
    }
}
