<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs;

use N1ebieski\KSEFClient\Support\Attributes\ArrayOf;
use N1ebieski\KSEFClient\Support\DTO;

final readonly class WarunkiTransakcji extends DTO
{
    /**
     * @param array<int, Zamowienia> $zamowienia
     * @return void
     */
    public function __construct(
        #[ArrayOf(Zamowienia::class)]
        public array $zamowienia = []
    ) {
    }
}
