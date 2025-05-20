<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts\Resources\Online\Query;

use N1ebieski\KSEFClient\Contracts\Resources\Online\Query\Invoice\InvoiceResourceInterface;

interface QueryResourceInterface
{
    public function invoice(): InvoiceResourceInterface;
}
