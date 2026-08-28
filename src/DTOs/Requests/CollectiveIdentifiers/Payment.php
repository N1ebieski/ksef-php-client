<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\CollectiveIdentifiers;

use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\ValueObjects\Requests\CollectiveIdentifiers\Amount;
use N1ebieski\KSEFClient\ValueObjects\Requests\Invoices\CurrencyCode;

final class Payment extends AbstractDTO
{
    public function __construct(
        public readonly Amount $amount,
        public readonly CurrencyCode $currency,
    ) {
    }
}
