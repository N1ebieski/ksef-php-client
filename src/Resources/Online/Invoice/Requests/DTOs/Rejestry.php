<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs;

use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\BDO;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\KRS;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\REGON;
use N1ebieski\KSEFClient\Support\DTO;

final readonly class Rejestry extends DTO
{
    /**
     * @param KRS|null $krs Numer Krajowego Rejestru Sądowego
     * @param BDO|null $bdo Numer w Bazie Danych o Odpadach
     * @return void
     */
    public function __construct(
        public ?KRS $krs = null,
        public ?REGON $regon = null,
        public ?BDO $bdo = null,
    ) {
    }
}
