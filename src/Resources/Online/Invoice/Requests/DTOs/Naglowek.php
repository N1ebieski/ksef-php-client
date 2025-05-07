<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs;

use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\SystemInfo;
use N1ebieski\KSEFClient\Resources\Online\ValueObjects\SystemCode;
use N1ebieski\KSEFClient\Support\DTO;

final readonly class Naglowek extends DTO
{
    public function __construct(
        public SystemCode $wariantFormularza,
        public ?SystemInfo $systemInfo = null,
    ) {
    }
}
