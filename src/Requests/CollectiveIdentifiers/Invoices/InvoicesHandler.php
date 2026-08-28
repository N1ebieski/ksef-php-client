<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\CollectiveIdentifiers\Invoices;

use N1ebieski\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\DTOs\HttpClient\Request;
use N1ebieski\KSEFClient\Requests\AbstractHandler;
use N1ebieski\KSEFClient\ValueObjects\HttpClient\Method;
use N1ebieski\KSEFClient\ValueObjects\HttpClient\Uri;

final class InvoicesHandler extends AbstractHandler
{
    public function __construct(
        private readonly HttpClientInterface $client,
    ) {
    }

    public function handle(InvoicesRequest $request): ResponseInterface
    {
        return $this->client->sendRequest(new Request(
            method: Method::Post,
            uri: Uri::from('collective-identifiers/invoices'),
            headers: $request->toHeaders(),
            parameters: $request->toParameters(),
            body: $request->toBody()
        ));
    }
}
