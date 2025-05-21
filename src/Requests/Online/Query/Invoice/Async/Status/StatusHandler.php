<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Query\Invoice\Async\Status;

use N1ebieski\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\HttpClient\DTOs\Request;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\Method;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\Uri;
use N1ebieski\KSEFClient\Requests\AbstractHandler;

final readonly class StatusHandler extends AbstractHandler
{
    public function __construct(
        private HttpClientInterface $client,
    ) {
    }

    public function handle(StatusRequest $request): ResponseInterface
    {
        return $this->client->sendRequest(new Request(
            method: Method::Get,
            uri: Uri::from(
                sprintf('online/Query/Invoice/Async/Status/%s', $request->queryElementReferenceNumber->value)
            ),
        ));
    }
}
