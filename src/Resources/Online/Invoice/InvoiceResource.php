<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice;

use N1ebieski\KSEFClient\Contracts\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Online\Invoice\InvoiceResourceInterface;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\Handlers\SendHandler;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\Responses\SendResponse;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\SendRequest;
use N1ebieski\KSEFClient\Resources\Resource;
use N1ebieski\KSEFClient\Support\Evaluation\Evaluation;

final readonly class InvoiceResource extends Resource implements InvoiceResourceInterface
{
    public function __construct(
        private HttpClientInterface $client,
    ) {
    }

    public function send(SendRequest | array $dto): SendResponse
    {
        /** @var SendRequest $dto */
        $dto = Evaluation::evaluate($dto, SendRequest::class);

        return new SendHandler($this->client)->handle($dto);
    }
}
