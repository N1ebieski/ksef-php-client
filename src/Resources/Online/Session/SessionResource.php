<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Session;

use N1ebieski\KSEFClient\Contracts\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Online\Session\SessionResourceInterface;
use N1ebieski\KSEFClient\Contracts\ResponseInterface;
use N1ebieski\KSEFClient\Resources\Online\Session\DTOs\AuthorisationChallengeRequest;
use N1ebieski\KSEFClient\Resources\Online\Session\DTOs\InitTokenRequest;
use N1ebieski\KSEFClient\Resources\Online\Session\Handlers\AuthorisationChallengeHandler;
use N1ebieski\KSEFClient\Resources\Online\Session\Handlers\InitTokenHandler;
use N1ebieski\KSEFClient\Resources\Online\Session\Handlers\TerminateHandler;
use N1ebieski\KSEFClient\Resources\Online\Session\Responses\AuthorisationChallengeResponse;
use N1ebieski\KSEFClient\Resources\Online\Session\Responses\InitTokenResponse;
use N1ebieski\KSEFClient\Resources\Resource;
use N1ebieski\KSEFClient\Support\Evaluation\Evaluation;

final readonly class SessionResource extends Resource implements SessionResourceInterface
{
    public function __construct(
        private HttpClientInterface $client,
    ) {
    }

    public function authorisationChallenge(AuthorisationChallengeRequest | array $dto): AuthorisationChallengeResponse
    {
        /** @var AuthorisationChallengeRequest $dto */
        $dto = Evaluation::evaluate($dto, AuthorisationChallengeRequest::class);

        return new AuthorisationChallengeHandler($this->client)->handle($dto);
    }

    public function initToken(InitTokenRequest | array $dto): InitTokenResponse
    {
        /** @var InitTokenRequest $dto */
        $dto = Evaluation::evaluate($dto, InitTokenRequest::class);

        return new InitTokenHandler($this->client)->handle($dto);
    }

    public function terminate(): ResponseInterface
    {
        return new TerminateHandler($this->client)->handle();
    }
}
