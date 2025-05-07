<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs;

use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\SystemInfo;
use N1ebieski\KSEFClient\Resources\Online\ValueObjects\SystemCode;
use N1ebieski\KSEFClient\Support\DTO;

final readonly class Naglowek extends DTO
{
    /**
     * @param null|SystemInfo $systemInfo Nazwa systemu teleinformatycznego, z którego korzysta podatnik
     * @return void
     */
    public function __construct(
        public SystemCode $wariantFormularza = SystemCode::Fa2,
        public ?SystemInfo $systemInfo = null,
    ) {
    }
}
