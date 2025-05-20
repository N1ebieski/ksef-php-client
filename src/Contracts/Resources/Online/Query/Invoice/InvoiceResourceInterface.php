<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts\Resources\Online\Query\Invoice;

use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\Requests\Online\Query\Invoice\Sync\SyncRequest;

interface InvoiceResourceInterface
{
    /**
     * @param SyncRequest|array<string, mixed> $request
     */
    public function sync(SyncRequest | array $request): ResponseInterface;
}
