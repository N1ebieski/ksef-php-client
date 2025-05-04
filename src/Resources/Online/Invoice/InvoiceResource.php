<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice;

use N1ebieski\KSEFClient\Contracts\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\ResponseInterface;
use N1ebieski\KSEFClient\Resources\Online\Invoice\DTOs\SendRequest;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Handlers\SendHandler;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Responses\SendResponse;
use N1ebieski\KSEFClient\Resources\Online\Session\DTOs\AuthorisationChallengeRequest;
use N1ebieski\KSEFClient\Resources\Online\Session\DTOs\InitTokenRequest;
use N1ebieski\KSEFClient\Resources\Online\Session\Handlers\AuthorisationChallengeHandler;
use N1ebieski\KSEFClient\Resources\Online\Session\Handlers\InitTokenHandler;
use N1ebieski\KSEFClient\Resources\Online\Session\Handlers\TerminateHandler;
use N1ebieski\KSEFClient\Resources\Online\Session\Responses\AuthorisationChallengeResponse;
use N1ebieski\KSEFClient\Resources\Online\Session\Responses\InitTokenResponse;
use N1ebieski\KSEFClient\Resources\Resource;

final readonly class InvoiceResource extends Resource
{
    public function __construct(
        private HttpClientInterface $client,
    ) {
    }

    public function send(SendRequest $dto): SendResponse
    {
        return new SendHandler($this->client)->handle($dto);
    }
}
