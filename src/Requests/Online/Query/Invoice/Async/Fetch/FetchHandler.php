<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Query\Invoice\Async\Fetch;

use N1ebieski\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\HttpClient\DTOs\Request;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\Header;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\Method;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\Uri;
use N1ebieski\KSEFClient\Requests\AbstractHandler;

final readonly class FetchHandler extends AbstractHandler
{
    public function __construct(
        private HttpClientInterface $client,
    ) {
    }

    public function handle(FetchRequest $request): ResponseInterface
    {
        return $this->client->sendRequest(new Request(
            method: Method::Get,
            headers: [
                new Header('Accept', 'application/octet-stream')
            ],
            uri: Uri::from(
                sprintf(
                    'online/Query/Invoice/Async/Fetch/%s/%s',
                    $request->queryElementReferenceNumber->value,
                    $request->partElementReferenceNumber->value
                )
            ),
        ));
    }
}
