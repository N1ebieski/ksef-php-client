<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs;

use N1ebieski\KSEFClient\Support\Attributes\ArrayOf;
use N1ebieski\KSEFClient\Support\DTO;

final readonly class Podmiot1 extends DTO
{
    /**
     * @param DaneIdentyfikacyjne $daneIdentyfikacyjne Dane identyfikujące podatnika
     * @param Adres $adres Adres podatnika
     * @param array<int, DaneKontaktowe> $daneKontaktowe Dane kontaktowe podatnika
     * @return void
     */
    public function __construct(
        public DaneIdentyfikacyjne $daneIdentyfikacyjne,
        public Adres $adres,
        #[ArrayOf(DaneKontaktowe::class)]
        public array $daneKontaktowe = []
    ) {
    }
}
