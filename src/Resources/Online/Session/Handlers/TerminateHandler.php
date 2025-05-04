<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Session\Handlers;

use N1ebieski\KSEFClient\HttpClient\DTOs\RequestDTO;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\Method;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\Uri;
use N1ebieski\KSEFClient\Contracts\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\ResponseInterface;
use N1ebieski\KSEFClient\Resources\Handler;

final readonly class TerminateHandler extends Handler
{
    public function __construct(
        private HttpClientInterface $client,
    ) {
    }

    public function handle(): ResponseInterface
    {
        $response = $this->client->sendRequest(new RequestDTO(
            uri: Uri::from('online/Session/Terminate')
        ));

        return $response;
    }
}
