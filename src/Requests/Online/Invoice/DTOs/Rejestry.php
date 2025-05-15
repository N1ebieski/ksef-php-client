<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\BDO;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\KRS;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\REGON;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class Rejestry extends AbstractDTO
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
