<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs;

use N1ebieski\KSEFClient\Support\DTO;

final readonly class Stopka extends DTO
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
