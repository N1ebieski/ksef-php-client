<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Session;

use N1ebieski\KSEFClient\Contracts\ClientHttpInterface;
use N1ebieski\KSEFClient\Contracts\ResponseInterface;
use N1ebieski\KSEFClient\Resources\Online\Session\DTOs\AuthorisationChallengeRequest;
use N1ebieski\KSEFClient\Resources\Online\Session\DTOs\InitTokenRequest;
use N1ebieski\KSEFClient\Resources\Online\Session\Handlers\AuthorisationChallengeHandler;
use N1ebieski\KSEFClient\Resources\Online\Session\Handlers\InitTokenHandler;
use N1ebieski\KSEFClient\Resources\Online\Session\Handlers\TerminateHandler;
use N1ebieski\KSEFClient\Resources\Online\Session\Responses\AuthorisationChallengeResponse;
use N1ebieski\KSEFClient\Resources\Online\Session\Responses\InitTokenResponse;

final readonly class SessionResource
{
    public function __construct(
        private ClientHttpInterface $client,
    ) {
    }

    public function authorisationChallenge(AuthorisationChallengeRequest $dto): AuthorisationChallengeResponse
    {
        return new AuthorisationChallengeHandler($this->client)->handle($dto);
    }

    public function initToken(InitTokenRequest $dto): InitTokenResponse
    {
        return new InitTokenHandler($this->client)->handle($dto);
    }

    public function terminate(): ResponseInterface
    {
        return new TerminateHandler($this->client)->handle();
    }
}
