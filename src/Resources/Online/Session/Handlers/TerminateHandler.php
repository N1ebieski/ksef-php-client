<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Session\Handlers;

use N1ebieski\KSEFClient\ClientHttp\DTOs\RequestDTO;
use N1ebieski\KSEFClient\ClientHttp\ValueObjects\Method;
use N1ebieski\KSEFClient\ClientHttp\ValueObjects\Uri;
use N1ebieski\KSEFClient\Contracts\ClientHttpInterface;
use N1ebieski\KSEFClient\Contracts\ResponseInterface;
use N1ebieski\KSEFClient\Resources\Handler;

final readonly class TerminateHandler extends Handler
{
    public function __construct(
        private ClientHttpInterface $client,
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
