<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts\Resources\Online\Session;

use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\Requests\Online\Session\AuthorisationChallenge\AuthorisationChallengeRequest;
use N1ebieski\KSEFClient\Requests\Online\Session\AuthorisationChallenge\AuthorisationChallengeResponse;
use N1ebieski\KSEFClient\Requests\Online\Session\InitSigned\InitSignedRequest;
use N1ebieski\KSEFClient\Requests\Online\Session\InitSigned\InitSignedResponse;
use N1ebieski\KSEFClient\Requests\Online\Session\InitSigned\InitSignedXmlRequest;
use N1ebieski\KSEFClient\Requests\Online\Session\InitToken\InitTokenRequest;
use N1ebieski\KSEFClient\Requests\Online\Session\InitToken\InitTokenResponse;
use N1ebieski\KSEFClient\Requests\Online\Session\Status\StatusRequest;
use N1ebieski\KSEFClient\Requests\Online\Session\Status\StatusResponse;
use N1ebieski\KSEFClient\Requests\Online\Session\Terminate\TerminateResponse;

interface SessionResourceInterface
{
    /**
     * @param AuthorisationChallengeRequest|array<string, mixed> $request
     */
    public function authorisationChallenge(AuthorisationChallengeRequest | array $request): ResponseInterface;

    /**
     * @param InitTokenRequest|array<string, mixed> $request
     */
    public function initToken(InitTokenRequest | array $request): ResponseInterface;

    /**
     * @param InitSignedRequest|InitSignedXmlRequest|array<string, mixed> $request
     */
    public function initSigned(InitSignedRequest | InitSignedXmlRequest | array $request): ResponseInterface;

    /**
     * @param StatusRequest|array<string, mixed> $request
     */
    public function status(StatusRequest | array $request = new StatusRequest()): ResponseInterface;

    public function terminate(): ResponseInterface;
}
