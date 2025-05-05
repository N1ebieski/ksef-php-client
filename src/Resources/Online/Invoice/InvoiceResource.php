<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice;

use N1ebieski\KSEFClient\Resources\Resource;

final readonly class InvoiceResource extends Resource
{
    public function __construct()
    {
    }

    // public function send(SendRequest | array $dto): SendResponse
    // {
    //     $dto = Evaluation::evaluate($dto, SendRequest::class);
    //
    //     return new SendHandler($this->client)->handle($dto);
    // }
}
