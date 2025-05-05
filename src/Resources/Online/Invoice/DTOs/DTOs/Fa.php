<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\DTOs\DTOs;

use N1ebieski\KSEFClient\Resources\Online\Invoice\DTOs\ValueObjects\KodWaluty;
use N1ebieski\KSEFClient\Resources\Online\Invoice\DTOs\ValueObjects\P_13_1;
use N1ebieski\KSEFClient\Resources\Online\Invoice\DTOs\ValueObjects\P_13_3;
use N1ebieski\KSEFClient\Resources\Online\Invoice\DTOs\ValueObjects\P_14_1;
use N1ebieski\KSEFClient\Resources\Online\Invoice\DTOs\ValueObjects\P_14_3;
use N1ebieski\KSEFClient\Resources\Online\Invoice\DTOs\ValueObjects\P_15;
use N1ebieski\KSEFClient\Resources\Online\Invoice\DTOs\ValueObjects\P_1;
use N1ebieski\KSEFClient\Resources\Online\Invoice\DTOs\ValueObjects\P_1M;
use N1ebieski\KSEFClient\Resources\Online\Invoice\DTOs\ValueObjects\P_2;
use N1ebieski\KSEFClient\Resources\Online\Invoice\DTOs\ValueObjects\P_6;
use N1ebieski\KSEFClient\Support\DTO;

final readonly class Fa extends DTO
{
    public function __construct(
        public KodWaluty $kodWaluty,
        public P_1 $p_1,
        public P_2 $p_2,
        public P_6 $p_6,
        public P_13_1 $p_13_1,
        public P_14_1 $p_14_1,
        public P_13_3 $p_13_3,
        public P_14_3 $p_14_3,
        public P_15 $p_15,
        public ?P_1M $p_1M = null,
    ) {
    }
}
