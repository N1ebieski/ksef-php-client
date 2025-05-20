<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts\Resources\Online;

use N1ebieski\KSEFClient\Contracts\Resources\Online\Invoice\InvoiceResourceInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Online\Query\QueryResourceInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Online\Session\SessionResourceInterface;

interface OnlineResourceInterface
{
    public function session(): SessionResourceInterface;

    public function invoice(): InvoiceResourceInterface;

    public function query(): QueryResourceInterface;
}
