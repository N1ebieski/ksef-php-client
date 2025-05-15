<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts\Resources\Online\Invoice;

use N1ebieski\KSEFClient\Requests\Online\Invoice\Send\SendResponse;
use N1ebieski\KSEFClient\Requests\Online\Invoice\Status\StatusResponse;
use N1ebieski\KSEFClient\Requests\Online\Invoice\Send\SendRequest;
use N1ebieski\KSEFClient\Requests\Online\Invoice\Status\StatusRequest;

interface InvoiceResourceInterface
{
    /**
     * @param SendRequest|array<string, mixed> $dto
     */
    public function send(SendRequest | array $dto): SendResponse;

    /**
     * @param StatusRequest|array<string, mixed> $dto
     */
    public function status(StatusRequest | array $dto): StatusResponse;
}
