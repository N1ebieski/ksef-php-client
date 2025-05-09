<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs;

use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\DataZamowienia;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\DataZaplaty;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\FormaPlatnosci;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\NrZamowienia;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\OpisPlatnosci;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\PlatnoscInna;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\Zaplacono;
use N1ebieski\KSEFClient\Support\DTO;

final readonly class PlatnoscInnaGroup extends DTO
{
    /**
     * @param PlatnoscInna $platnoscInna Znacznik innej formy płatności: 1 - inna forma płatności
     * @param OpisPlatnosci $opisPlatnosci Opis płatnosci Doprecyzowanie innej formy płatnośc
     * @return void
     */
    public function __construct(
        public OpisPlatnosci $opisPlatnosci,
        public PlatnoscInna $platnoscInna = PlatnoscInna::Default,
    ) {
    }
}
