<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs;

use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\AdresL1;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\AdresL2;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\Klucz;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\KodKraju;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\NrWiersza;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\Wartosc;
use N1ebieski\KSEFClient\Support\DTO;

final readonly class DodatkowyOpis extends DTO
{
    /**
     * @param null|NrWiersza $nrWiersza Numer wiersza podany w polu NrWierszaFa lub NrWierszaZam, jeśli informacja odnosi się wyłącznie do danej pozycji faktury
     * @return void
     */
    public function __construct(
        public ?NrWiersza $nrWiersza = null,
        public Klucz $klucz,
        public Wartosc $wartosc,
    ) {
    }
}
