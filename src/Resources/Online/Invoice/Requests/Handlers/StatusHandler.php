<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\Handlers;

use N1ebieski\KSEFClient\Contracts\HttpClientInterface;
use N1ebieski\KSEFClient\HttpClient\DTOs\Request;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\Method;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\Uri;
use N1ebieski\KSEFClient\Resources\Handler;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\Responses\StatusResponse;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\StatusRequest;

final readonly class StatusHandler extends Handler
{
    public function __construct(
        private HttpClientInterface $client,
    ) {
    }

    public function handle(StatusRequest $dto): StatusResponse
    {
        $response = $this->client->sendRequest(new Request(
            method: Method::Get,
            uri: Uri::from(
                sprintf('online/Invoice/Status/%s', $dto->invoiceElementReferenceNumber->value)
            ),
        ));

        return StatusResponse::fromResponse($response);
    }
}
