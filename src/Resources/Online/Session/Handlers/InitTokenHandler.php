<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Session\Handlers;

use N1ebieski\KSEFClient\Contracts\HttpClientInterface;
use N1ebieski\KSEFClient\HttpClient\DTOs\Request;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\Header;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\Method;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\Uri;
use N1ebieski\KSEFClient\Resources\Handler;
use N1ebieski\KSEFClient\Resources\Online\Session\DTOs\InitTokenRequest;
use N1ebieski\KSEFClient\Resources\Online\Session\Responses\InitTokenResponse;

final readonly class InitTokenHandler extends Handler
{
    public function __construct(
        private HttpClientInterface $client,
    ) {
    }

    public function handle(InitTokenRequest $dto): InitTokenResponse
    {
        $response = $this->client->sendRequest(new Request(
            method: Method::Post,
            uri: Uri::from('online/Session/InitToken'),
            headers: [
                new Header('Content-Type', 'application/octet-stream')
            ],
            data: $dto->toXml()
        ));

        return InitTokenResponse::fromResponse($response);
    }
}
