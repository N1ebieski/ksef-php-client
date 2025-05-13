<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts\Resources\Online\Session;

use N1ebieski\KSEFClient\Contracts\ResponseInterface;
use N1ebieski\KSEFClient\Resources\Online\Session\Requests\AuthorisationChallengeRequest;
use N1ebieski\KSEFClient\Resources\Online\Session\Requests\InitSignedRequest;
use N1ebieski\KSEFClient\Resources\Online\Session\Requests\InitTokenRequest;
use N1ebieski\KSEFClient\Resources\Online\Session\Requests\Responses\AuthorisationChallengeResponse;
use N1ebieski\KSEFClient\Resources\Online\Session\Requests\Responses\InitSignedResponse;
use N1ebieski\KSEFClient\Resources\Online\Session\Requests\Responses\InitTokenResponse;

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

    /**
     * @param InitSignedRequest|array<string, mixed> $dto
     */
    public function initSigned(InitSignedRequest | array $dto): InitSignedResponse;

    public function terminate(): ResponseInterface;
}
