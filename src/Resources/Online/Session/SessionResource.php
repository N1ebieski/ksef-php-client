<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Session;

use N1ebieski\KSEFClient\Contracts\ClientHttpInterface;
use N1ebieski\KSEFClient\Resources\Online\Session\DTOs\AuthorisationChallengeDTO;
use N1ebieski\KSEFClient\Resources\Online\Session\Handlers\AuthorisationChallengeHandler;
use N1ebieski\KSEFClient\Resources\Online\Session\Responses\AuthorisationChallengeResponse;
use Psr\Http\Message\ResponseInterface;

final readonly class SessionResource
{
    public function __construct(
        private ClientHttpInterface $client,
    ) {
    }

    public function authorisationChallenge(AuthorisationChallengeDTO $dto): AuthorisationChallengeResponse
    {
        return new AuthorisationChallengeHandler($this->client)->handle($dto);
    }
}
