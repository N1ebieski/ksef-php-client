<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class Stopka extends AbstractDTO
{
    /**
     * @param array<int, Informacje> $informacje Pozostałe dane
     * @param array<int, Rejestry> $rejestry
     * @return void
     */
    public function __construct(
        public array $informacje = [],
        public array $rejestry = []
    ) {
    }
}
