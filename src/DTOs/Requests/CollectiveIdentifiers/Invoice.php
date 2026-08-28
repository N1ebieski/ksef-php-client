<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\CollectiveIdentifiers;

use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\ValueObjects\Requests\KsefNumber;

final class Invoice extends AbstractDTO
{
    public function __construct(
        public readonly KsefNumber $ksefNumber,
        public readonly Optional | Payment $payment = new Optional(),
        public readonly Optional | string $description = new Optional(),
    ) {
    }
}
