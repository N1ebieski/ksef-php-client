<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Session\Terminate;

use N1ebieski\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use N1ebieski\KSEFClient\HttpClient\DTOs\Request;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\Uri;
use N1ebieski\KSEFClient\Requests\AbstractHandler;

final readonly class TerminateHandler extends AbstractHandler
{
    public function __construct(
        private HttpClientInterface $client,
    ) {
    }

    public function handle(): TerminateResponse
    {
        $response = $this->client->sendRequest(new Request(
            uri: Uri::from('online/Session/Terminate')
        ));

        return TerminateResponse::fromResponse($response);
    }
}
