<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice;

use N1ebieski\KSEFClient\Contracts\HttpClientInterface;
use N1ebieski\KSEFClient\Resources\Online\Invoice\DTOs\SendRequest;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Handlers\SendHandler;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Responses\SendResponse;
use N1ebieski\KSEFClient\Resources\Resource;

final readonly class InvoiceResource extends Resource
{
    public function __construct(
        private HttpClientInterface $client,
    ) {
    }

    public function send(SendRequest $dto): SendResponse
    {
        return new SendHandler($this->client)->handle($dto);
    }
}
