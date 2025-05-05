<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts\Resources\Online\Session;

use N1ebieski\KSEFClient\Contracts\ResponseInterface;
use N1ebieski\KSEFClient\Resources\Online\Session\DTOs\AuthorisationChallengeRequest;
use N1ebieski\KSEFClient\Resources\Online\Session\DTOs\InitTokenRequest;
use N1ebieski\KSEFClient\Resources\Online\Session\Responses\AuthorisationChallengeResponse;
use N1ebieski\KSEFClient\Resources\Online\Session\Responses\InitTokenResponse;

interface SessionResourceInterface
{
    /**
     * @param AuthorisationChallengeRequest|array<string, mixed> $dto
     */
    public function authorisationChallenge(AuthorisationChallengeRequest | array $dto): AuthorisationChallengeResponse;

    /**
     * @param InitTokenRequest|array<string, mixed> $dto
     */
    public function initToken(InitTokenRequest | array $dto): InitTokenResponse;

    public function terminate(): ResponseInterface;
}
