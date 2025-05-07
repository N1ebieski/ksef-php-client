<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts\Resources\Online\Invoice;

use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\Responses\SendResponse;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\SendRequest;

interface InvoiceResourceInterface
{
    /**
     * @param SendRequest|array<string, mixed> $dto
     */
    public function send(SendRequest | array $dto): SendResponse;
}
