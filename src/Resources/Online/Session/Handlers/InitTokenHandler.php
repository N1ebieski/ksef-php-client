<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Session\Handlers;

use N1ebieski\KSEFClient\ClientHttp\DTOs\RequestDTO;
use N1ebieski\KSEFClient\ClientHttp\ValueObjects\Header;
use N1ebieski\KSEFClient\ClientHttp\ValueObjects\Method;
use N1ebieski\KSEFClient\ClientHttp\ValueObjects\Uri;
use N1ebieski\KSEFClient\Contracts\ClientHttpInterface;
use N1ebieski\KSEFClient\Resources\Handler;
use N1ebieski\KSEFClient\Resources\Online\Session\DTOs\InitTokenRequest;
use N1ebieski\KSEFClient\Resources\Online\Session\Responses\InitTokenResponse;

final readonly class InitTokenHandler extends Handler
{
    public function __construct(
        private ClientHttpInterface $client,
    ) {
    }

    public function handle(InitTokenRequest $dto): InitTokenResponse
    {
        $xml = $dto->toXml();

        $response = $this->client->sendRequest(new RequestDTO(
            method: Method::Post,
            uri: Uri::from('online/Session/InitToken'),
            headers: [
                new Header('Content-Type', 'application/octet-stream')
            ],
            data: $xml
        ));

        return InitTokenResponse::fromResponse($response);
    }
}
