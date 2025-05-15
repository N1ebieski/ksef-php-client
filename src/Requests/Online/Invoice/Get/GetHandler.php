<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\Get;

use N1ebieski\KSEFClient\Contracts\HttpClientInterface;
use N1ebieski\KSEFClient\HttpClient\DTOs\Request;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\Header;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\Method;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\Uri;
use N1ebieski\KSEFClient\Requests\AbstractHandler;

final readonly class GetHandler extends AbstractHandler
{
    public function __construct(
        private HttpClientInterface $client,
    ) {
    }

    public function handle(GetRequest $request): GetResponse
    {
        $response = $this->client->sendRequest(new Request(
            method: Method::Get,
            uri: Uri::from(
                sprintf('online/Invoice/Get/%s', $request->ksefReferenceNumber->value)
            ),
            headers: [
                new Header('Accept', 'application/octet-stream')
            ]
        ));

        return GetResponse::fromResponse($response);
    }
}
