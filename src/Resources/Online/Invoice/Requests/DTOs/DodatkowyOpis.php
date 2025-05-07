<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs;

use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\Klucz;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\NrWiersza;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\Wartosc;
use N1ebieski\KSEFClient\Support\DTO;

final readonly class DodatkowyOpis extends DTO
{
    /**
     * @param NrWiersza|null $nrWiersza Numer wiersza podany w polu NrWierszaFa lub NrWierszaZam, jeśli informacja odnosi się wyłącznie do danej pozycji faktury
     * @return void
     */
    public function __construct(
        public Klucz $klucz,
        public Wartosc $wartosc,
        public ?NrWiersza $nrWiersza = null,
    ) {
    }
}
