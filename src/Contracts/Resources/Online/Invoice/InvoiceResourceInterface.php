<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts\Resources\Online\Invoice;

use N1ebieski\KSEFClient\Requests\Online\Invoice\Get\GetRequest;
use N1ebieski\KSEFClient\Requests\Online\Invoice\Get\GetResponse;
use N1ebieski\KSEFClient\Requests\Online\Invoice\Send\SendRequest;
use N1ebieski\KSEFClient\Requests\Online\Invoice\Send\SendResponse;
use N1ebieski\KSEFClient\Requests\Online\Invoice\Status\StatusRequest;
use N1ebieski\KSEFClient\Requests\Online\Invoice\Status\StatusResponse;

interface InvoiceResourceInterface
{
    /**
     * @param SendRequest|array<string, mixed> $request
     */
    public function send(SendRequest | array $request): SendResponse;

    /**
     * @param StatusRequest|array<string, mixed> $request
     */
    public function status(StatusRequest | array $request): StatusResponse;

    /**
     * @param GetRequest|array<string, mixed> $request
     */
    public function get(GetRequest | array $request): GetResponse;
}
