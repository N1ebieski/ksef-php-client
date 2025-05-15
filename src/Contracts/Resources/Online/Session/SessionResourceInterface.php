<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts\Resources\Online\Session;

use N1ebieski\KSEFClient\Contracts\ResponseInterface;
use N1ebieski\KSEFClient\Requests\Online\Session\AuthorisationChallenge\AuthorisationChallengeRequest;
use N1ebieski\KSEFClient\Requests\Online\Session\AuthorisationChallenge\AuthorisationChallengeResponse;
use N1ebieski\KSEFClient\Requests\Online\Session\InitSigned\InitSignedRequest;
use N1ebieski\KSEFClient\Requests\Online\Session\InitSigned\InitSignedResponse;
use N1ebieski\KSEFClient\Requests\Online\Session\InitSigned\InitSignedXmlRequest;
use N1ebieski\KSEFClient\Requests\Online\Session\InitToken\InitTokenRequest;
use N1ebieski\KSEFClient\Requests\Online\Session\InitToken\InitTokenResponse;

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
     * @param InitSignedRequest|InitSignedXmlRequest|array<string, mixed> $dto
     */
    public function initSigned(InitSignedRequest | InitSignedXmlRequest | array $dto): InitSignedResponse;

    public function terminate(): ResponseInterface;
}
